<template>
  <div class="container mt-4">
    <h2>Add Product</h2>

    <form action="/products/store" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="_token" :value="csrf">

      <label>Name</label>
      <input name="name" class="form-control mb-2" v-model="form.name">

      <label>Main Image</label>
      <input type="file" name="image" class="form-control mb-2">

      <label>Image Alt Text (SEO)</label>
      <input name="alt_text" class="form-control mb-2" v-model="form.alt_text" placeholder="e.g. Red running shoes, side view">

      <label>Size</label>
      <input name="size" class="form-control mb-2" v-model="form.size">

      <label>Price</label>
      <input name="price" type="number" class="form-control mb-2" v-model.number="form.price">

      <hr>
      <h4>SEO & OG Details</h4>

      <label>SEO Image</label>
      <input type="file" name="seo_image" class="form-control mb-2">
      <label>SEO Image Alt Text</label>
      <input name="seo_image_alt" class="form-control mb-2" v-model="form.seo_image_alt">

      <label class="mt-2">OG Image</label>
      <input type="file" name="og_image" class="form-control mb-2">
      <label>OG Image Alt Text</label>
      <input name="og_image_alt" class="form-control mb-2" v-model="form.og_image_alt">

      <label>SEO Meta Title</label>
      <input name="seo_meta_title" class="form-control mb-2" v-model="form.seo_meta_title" maxlength="60">

      <label>OG Meta Title</label>
      <input name="og_meta_title" class="form-control mb-2" v-model="form.og_meta_title" maxlength="60">

      <label>SEO Meta Keywords</label>
      <textarea name="seo_meta_keywords" class="form-control mb-2" v-model="form.seo_meta_keywords"></textarea>

      <label>OG Meta Keywords</label>
      <textarea name="og_meta_keywords" class="form-control mb-2" v-model="form.og_meta_keywords"></textarea>

      <label>SEO Meta Description</label>
      <textarea name="seo_meta_description" class="form-control mb-2" v-model="form.seo_meta_description" maxlength="160"></textarea>

      <label>OG Meta Description</label>
      <textarea name="og_meta_description" class="form-control mb-2" v-model="form.og_meta_description" maxlength="110"></textarea>

      <label>SEO Canonical URL</label>
      <input name="seo_canonical" class="form-control mb-2" v-model="form.seo_canonical">

      <label>Meta Robots</label>
      <select name="meta_robots" class="form-control mb-2" v-model="form.meta_robots">
        <option value="index,follow">index,follow</option>
        <option value="noindex,nofollow">noindex,nofollow</option>
      </select>

      <SeoPreview
        :title="form.seo_meta_title || form.name"
        :description="form.seo_meta_description"
        :og-title="form.og_meta_title || form.name"
        :og-description="form.og_meta_description"
        :product-url="previewUrl"
      />

      <button class="btn btn-success">Save</button>
      <router-link to="/products" class="btn btn-secondary">Back</router-link>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        name: '',
        size: '',
        price: '',
        alt_text: '',
        seo_image_alt: '',
        og_image_alt: '',
        seo_meta_title: '',
        og_meta_title: '',
        seo_meta_keywords: '',
        og_meta_keywords: '',
        seo_meta_description: '',
        og_meta_description: '',
        seo_canonical: '',
        meta_robots: 'index,follow',
      },
    };
  },
  computed: {
    csrf() {
      return document.querySelector('meta[name=csrf-token]').content;
    },
    previewUrl() {
      const base = window.location.origin;
      return base + '/products';
    },
  },
};
</script>
