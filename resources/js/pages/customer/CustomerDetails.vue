<template>
  <div class="container mt-4">

    <button class="btn btn-secondary mb-3" @click="$router.back()">Back</button>

    <div class="row">

      <div class="col-md-5">
        <img 
          v-if="product.image"
          :src="'/product_images/' + product.image"
          class="img-fluid rounded shadow-sm"
        >
      </div>

      <div class="col-md-7">
        <h2>{{ product.name }}</h2>

        <p><strong>Size:</strong> {{ product.size }}</p>
        <p><strong>Price:</strong> ₹{{ product.price }}</p>

        <!-- ❗ NO SEO fields shown here -->
      </div>

    </div>
  </div>
</template>

<script>
export default {
  props: ["id"],

  data() {
    return { product: {} }
  },

  methods: {
    addMetaTags() {
      // Clear previous meta tags (optional)
      document.querySelectorAll('meta[data-dynamic="true"]').forEach(m => m.remove());

      // Function to add meta
      const addMeta = (attr, content) => {
        if (content) {
          let m = document.createElement('meta');
          m.setAttribute(attr.type, attr.name);
          m.setAttribute('content', content);
          m.setAttribute('data-dynamic', 'true');
          document.head.appendChild(m);
        }
      };

      // SEO TAGS
      addMeta({ type: 'name', name: 'description' }, this.product.seo_meta_description);
      addMeta({ type: 'name', name: 'keywords' }, this.product.seo_meta_keywords);
      addMeta({ type: 'name', name: 'title' }, this.product.seo_meta_title);

      // OG TAGS
      addMeta({ type: 'property', name: 'og:title' }, this.product.og_meta_title);
      addMeta({ type: 'property', name: 'og:description' }, this.product.og_meta_description);
      addMeta({ type: 'property', name: 'og:image' }, '/product_images/' + this.product.og_image);

      // Canonical
      if (this.product.seo_canonical) {
        let link = document.createElement('link');
        link.setAttribute('rel', 'canonical');
        link.setAttribute('href', this.product.seo_canonical);
        link.setAttribute('data-dynamic', 'true');
        document.head.appendChild(link);
      }

      // Update <title>
      if (this.product.seo_meta_title) {
        document.title = this.product.seo_meta_title;
      }
    }
  },

  mounted() {
    fetch('/product-data/' + this.id)
      .then(res => res.json())
      .then(data => {
        this.product = data;
        this.addMetaTags();   // 🔥 Important: SEO tags added to HEAD
      });
  }
}
</script>
