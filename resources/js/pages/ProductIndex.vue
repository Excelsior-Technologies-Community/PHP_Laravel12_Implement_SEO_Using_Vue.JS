<template>
  <div class="container mt-4">
    <h2>Product List</h2>

    <router-link to="/products/create" class="btn btn-primary mb-2">Add Product</router-link>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Name</th>
          <th>Image</th>
          <th>Size</th>
          <th>Price</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="p in products" :key="p.id">
          <td>{{ p.name }}</td>

          <td>
            <img v-if="p.image" :src="'/product_images/' + p.image" width="60">
          </td>

          <td>{{ p.size }}</td>
          <td>{{ p.price }}</td>

          <td>
            <router-link :to="'/products/edit/' + p.id" class="btn btn-warning btn-sm">Edit</router-link>
            <a :href="'/products/delete/' + p.id" class="btn btn-danger btn-sm">Delete</a>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      products: []
    }
  },

  mounted() {
    fetch('/products-data')
      .then(res => res.json())
      .then(data => this.products = data)
  }
}
</script>
