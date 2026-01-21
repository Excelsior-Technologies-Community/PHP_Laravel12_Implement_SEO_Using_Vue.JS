<template>
  <div class="container mt-4">
    <h2>Edit Product</h2>

    <form :action="'/products/update/' + product.id" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="_token" :value="csrf">

      <!-- PRODUCT MAIN DETAILS -->
      <label>Name</label>
      <input name="name" class="form-control mb-2" :value="product.name">

      <label>Current Image</label><br>
      <img :src="'/product_images/' + product.image" width="80" v-if="product.image">

      <label class="mt-2">Change Image</label>
      <input type="file" name="image" class="form-control mb-2">

      <label>Size</label>
      <input name="size" class="form-control mb-2" :value="product.size">

      <label>Price</label>
      <input name="price" type="number" class="form-control mb-2" :value="product.price">

      <hr>
      <h4>SEO & OG Details</h4>

      <!-- SEO IMAGE -->
      <label>Current SEO Image</label><br>
      <img :src="'/product_images/' + product.seo_image" width="80" v-if="product.seo_image">

      <label class="mt-2">Change SEO Image</label>
      <input type="file" name="seo_image" class="form-control mb-2">

      <!-- OG IMAGE -->
      <label>Current OG Image</label><br>
      <img :src="'/product_images/' + product.og_image" width="80" v-if="product.og_image">

      <label class="mt-2">Change OG Image</label>
      <input type="file" name="og_image" class="form-control mb-2">

      <!-- SEO TEXT FIELDS -->
      <label>SEO Meta Title</label>
      <input name="seo_meta_title" class="form-control mb-2" :value="product.seo_meta_title">

      <label>OG Meta Title</label>
      <input name="og_meta_title" class="form-control mb-2" :value="product.og_meta_title">

      <label>SEO Meta Keywords</label>
      <textarea name="seo_meta_keywords" class="form-control mb-2">{{ product.seo_meta_keywords }}</textarea>

      <label>OG Meta Keywords</label>
      <textarea name="og_meta_keywords" class="form-control mb-2">{{ product.og_meta_keywords }}</textarea>

      <label>SEO Meta Description</label>
      <textarea name="seo_meta_description" class="form-control mb-2">{{ product.seo_meta_description }}</textarea>

      <label>OG Meta Description</label>
      <textarea name="og_meta_description" class="form-control mb-2">{{ product.og_meta_description }}</textarea>

      <label>SEO Canonical URL</label>
      <input name="seo_canonical" class="form-control mb-2" :value="product.seo_canonical">

      <button class="btn btn-primary">Update</button>
      <router-link to="/products" class="btn btn-secondary">Back</router-link>
    </form>
  </div>
</template>

<script>
export default {
  props: ["id"],

  data() {
    return { product: {} }
  },

  mounted() {
    fetch('/product-data/' + this.id)
      .then(res => res.json())
      .then(data => this.product = data)
  },

  computed: {
    csrf() {
      return document.querySelector('meta[name=csrf-token]').content;
    }
  }
}
</script>
