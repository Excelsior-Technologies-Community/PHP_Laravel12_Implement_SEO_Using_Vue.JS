<template>
  <div class="container mt-4">
    <h2>Edit Product</h2>

    <form :action="`/products/update/${product.id}`" method="POST" enctype="multipart/form-data" v-if="product">
      <input type="hidden" name="_token" :value="csrf">

      <label>Name</label>
      <input name="name" class="form-control mb-2" v-model="product.name">

      <label>Slug</label>
      <input name="slug" class="form-control mb-2" v-model="product.slug">

      <label>Current Image</label><br>
      <img :src="product.image_url" width="80" v-if="product.image_url">

      <label class="mt-2">Change Image</label>
      <input type="file" name="image" class="form-control mb-2">

      <label>Image Alt Text (SEO)</label>
      <input name="alt_text" class="form-control mb-2" v-model="product.alt_text" placeholder="e.g. Red running shoes, side view">

      <label class="mt-2">Size</label>
      <input name="size" class="form-control mb-2" v-model="product.size">

      <label>Price</label>
      <input name="price" type="number" class="form-control mb-2" v-model.number="product.price">

      <hr>
      <h4>SEO & OG Details</h4>

      <label>Current SEO Image</label><br>
      <img :src="product.seo_image_url" width="80" v-if="product.seo_image_url">
      <label class="mt-2">Change SEO Image</label>
      <input type="file" name="seo_image" class="form-control mb-2">
      <label>SEO Image Alt Text</label>
      <input name="seo_image_alt" class="form-control mb-2" v-model="product.seo_image_alt">

      <label class="mt-2">Current OG Image</label><br>
      <img :src="product.og_image_url" width="80" v-if="product.og_image_url">
      <label class="mt-2">Change OG Image</label>
      <input type="file" name="og_image" class="form-control mb-2">
      <label>OG Image Alt Text</label>
      <input name="og_image_alt" class="form-control mb-2" v-model="product.og_image_alt">

      <label>SEO Meta Title</label>
      <input name="seo_meta_title" class="form-control mb-2" v-model="product.seo_meta_title" maxlength="60">

      <label>OG Meta Title</label>
      <input name="og_meta_title" class="form-control mb-2" v-model="product.og_meta_title" maxlength="60">

      <label>SEO Meta Keywords</label>
      <textarea name="seo_meta_keywords" class="form-control mb-2" v-model="product.seo_meta_keywords"></textarea>

      <label>OG Meta Keywords</label>
      <textarea name="og_meta_keywords" class="form-control mb-2" v-model="product.og_meta_keywords"></textarea>

      <label>SEO Meta Description</label>
      <textarea name="seo_meta_description" class="form-control mb-2" v-model="product.seo_meta_description" maxlength="160"></textarea>

      <label>OG Meta Description</label>
      <textarea name="og_meta_description" class="form-control mb-2" v-model="product.og_meta_description" maxlength="110"></textarea>

      <label>SEO Canonical URL</label>
      <input name="seo_canonical" class="form-control mb-2" v-model="product.seo_canonical">

      <label>Meta Robots</label>
      <select name="meta_robots" class="form-control mb-2" v-model="product.meta_robots">
        <option value="index,follow">index,follow</option>
        <option value="noindex,nofollow">noindex,nofollow</option>
      </select>

      <SeoPreview
        :title="product.seo_meta_title || product.name"
        :description="product.seo_meta_description"
        :og-title="product.og_meta_title || product.name"
        :og-description="product.og_meta_description"
        :og-image="product.og_image_url"
        :product-url="previewUrl"
      />

      <button class="btn btn-primary">Update</button>
      <router-link to="/products" class="btn btn-secondary">Back</router-link>
    </form>
  </div>
</template>

<script>
export default {
  props: ['id'],

  data() {
    return {
      product: null,
    };
  },

  computed: {
    csrf() {
      return document.querySelector('meta[name=csrf-token]').content;
    },
    previewUrl() {
      if (this.product && this.product.slug) {
        return `${window.location.origin}/product/${this.product.slug}`;
      }
      return `${window.location.origin}/products`;
    },
  },

  mounted() {
    fetch(`/product-data/${this.id}`)
      .then((res) => res.json())
      .then((data) => {
        this.product = data;
      });
  },
};
</script>
