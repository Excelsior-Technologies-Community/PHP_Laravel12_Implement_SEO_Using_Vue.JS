<template>
  <div class="container mt-4">

    <h2 class="text-center mb-4">Products</h2>

    <div class="row">
      <div class="col-md-4 mb-4" v-for="p in products" :key="p.id">
        <div class="card shadow-sm">

          <img
            v-if="p.image_url"
            :src="p.image_url"
            :alt="p.alt_text || p.name"
            class="card-img-top"
            loading="lazy"
            width="300"
            height="230"
          >

          <div class="card-body">

            <h5 class="card-title">{{ p.name }}</h5>

            <p class="mb-1"><strong>Size:</strong> {{ p.size }}</p>
            <p class="mb-1"><strong>Price:</strong> ₹{{ p.price }}</p>

            <router-link 
              :to="'/customer/products/' + p.id"
              class="btn btn-primary w-100 mt-2"
            >
              View
            </router-link>

          </div>

        </div>
      </div>
    </div>

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
