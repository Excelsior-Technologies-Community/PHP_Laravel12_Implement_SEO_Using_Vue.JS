<template>
  <div class="container mt-4" v-if="product">
    <button class="btn btn-secondary mb-3" @click="$router.back()">Back</button>

    <div class="row g-4">
      <div class="col-md-5">

        <div v-if="product.image_url" class="mb-3 text-center">
          <img
            :src="product.image_url"
            :alt="product.alt_text || product.name"
            class="img-fluid rounded shadow-sm"
            loading="lazy"
            width="400"
            height="400"
          >
        </div>

        <div class="row g-2">
          <div class="col-6">
            <img
              v-if="product.seo_image_url"
              :src="product.seo_image_url"
              :alt="product.seo_image_alt || product.alt_text || product.name"
              class="img-fluid rounded"
              loading="lazy"
            >
            <small class="text-muted d-block mt-1">SEO Image</small>
          </div>
          <div class="col-6">
            <img
              v-if="product.og_image_url"
              :src="product.og_image_url"
              :alt="product.og_image_alt || product.alt_text || product.name"
              class="img-fluid rounded"
              loading="lazy"
            >
            <small class="text-muted d-block mt-1">OG Image</small>
          </div>
        </div>
      </div>

      <div class="col-md-7">
        <h2>{{ product.name }}</h2>

        <p><strong>Size:</strong> {{ product.size }}</p>
        <p><strong>Price:</strong> ₹{{ product.price }}</p>

        <div v-if="product.seo_meta_description" class="border-start border-primary ps-3 mb-3">
          <p class="mb-0 text-muted fst-italic">{{ product.seo_meta_description }}</p>
        </div>

        <router-link
          v-if="product.slug"
          :to="`/customer/products/` + product.id"
          class="btn btn-outline-primary btn-sm"
        >
          Shareable link
        </router-link>
      </div>
    </div>

    <div v-if="product.product_url" class="alert alert-info mt-3 mb-0">
      Canonical: {{ product.product_url }}
    </div>
  </div>

  <div v-else class="container mt-4">
    <p>Loading product…</p>
  </div>
</template>

<script>
export default {
  props: {
    id: { type: [String, Number], required: true },
  },

  data() {
    return {
      product: null,
    };
  },

  methods: {
    applyMeta(product) {
      document.querySelectorAll('meta[data-dynamic="true"]').forEach((m) => m.remove());
      document.querySelectorAll('link[data-dynamic="true"]').forEach((l) => l.remove());

      const setMeta = (type, name, content) => {
        if (!content) return;
        const m = document.createElement('meta');
        m.setAttribute(type, name);
        m.setAttribute('content', content);
        m.setAttribute('data-dynamic', 'true');
        document.head.appendChild(m);
      };

      if (product.seo_meta_title) {
        document.title = product.seo_meta_title;
      }
      setMeta('name', 'description', product.seo_meta_description);
      setMeta('name', 'keywords', product.seo_meta_keywords);
      setMeta('property', 'og:title', product.og_meta_title || product.seo_meta_title);
      setMeta('property', 'og:description', product.og_meta_description);
      if (product.og_image_url) {
        setMeta('property', 'og:image', product.og_image_url);
      }
      if (product.seo_canonical) {
        const link = document.createElement('link');
        link.setAttribute('rel', 'canonical');
        link.setAttribute('href', product.seo_canonical);
        link.setAttribute('data-dynamic', 'true');
        document.head.appendChild(link);
      }
    },
  },

  mounted() {
    fetch(`/product-data/${this.id}`)
      .then((res) => res.json())
      .then((data) => {
        this.product = data;
        this.applyMeta(this.product);
      });
  },

  watch: {
    '$route.params.id'(id) {
      fetch(`/product-data/${id}`)
        .then((res) => res.json())
        .then((data) => {
          this.product = data;
          this.applyMeta(this.product);
        });
    },
  },
};
</script>
