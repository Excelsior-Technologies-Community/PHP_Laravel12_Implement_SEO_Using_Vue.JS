<template>
  <div class="container mt-4">
    <h2>Add Product</h2>

    <form action="/products/store" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="_token" :value="csrf">

      <label>Name</label>
      <input name="name" class="form-control mb-2">

      <label>Image</label>
      <input type="file" name="image" class="form-control mb-2">

      <label>Size</label>
      <input name="size" class="form-control mb-2">

      <label>Price</label>
      <input name="price" type="number" class="form-control mb-2">

      <hr>
      <h4>SEO & OG Details</h4>

      <label>SEO Image</label>
      <input type="file" name="seo_image" class="form-control mb-2">

      <label>OG Image</label>
      <input type="file" name="og_image" class="form-control mb-2">

      <label>SEO Meta Title</label>
      <input name="seo_meta_title" class="form-control mb-2">

      <label>OG Meta Title</label>
      <input name="og_meta_title" class="form-control mb-2">

      <label>SEO Meta Keywords</label>
      <textarea name="seo_meta_keywords" class="form-control mb-2"></textarea>

      <label>OG Meta Keywords</label>
      <textarea name="og_meta_keywords" class="form-control mb-2"></textarea>

      <label>SEO Meta Description</label>
      <textarea name="seo_meta_description" class="form-control mb-2"></textarea>

      <label>OG Meta Description</label>
      <textarea name="og_meta_description" class="form-control mb-2"></textarea>

      <label>SEO Canonical URL</label>
      <input name="seo_canonical" class="form-control mb-2">

      <button class="btn btn-success">Save</button>
      <router-link to="/products" class="btn btn-secondary">Back</router-link>
    </form>
  </div>
</template>

<script>
export default {
  computed: {
    csrf() {
      return document.querySelector('meta[name=csrf-token]').content;
    }
  }
}
</script>
