<template>

  <div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

      <h2>Product List</h2>

      <router-link
        to="/products/create"
        class="btn btn-primary"
      >
        Add Product
      </router-link>

    </div>

    <table class="table table-bordered table-hover">

      <thead>

        <tr>

          <th>Name</th>

          <th>Image</th>

          <th>Size</th>

          <th>Price</th>

          <th>SEO Score</th>

          <th>Action</th>

        </tr>

      </thead>

      <tbody>

        <tr
          v-for="p in products"
          :key="p.id"
        >

          <td>
            {{ p.name }}
          </td>

          <td>

            <img
              v-if="p.image_url"
              :src="p.image_url"
              :alt="p.alt_text || p.name"
              width="60"
              height="60"
              loading="lazy"
              class="rounded"
            >

            <span v-else>
              No image
            </span>

          </td>

          <td>
            {{ p.size }}
          </td>

          <td>
            ₹{{ p.price }}
          </td>

          <td>

            <span
              class="badge"
              :class="scoreBadge(p.seo_score)"
            >
              {{ p.seo_score }}/100
            </span>

          </td>

          <td>

            <router-link
              :to="'/products/edit/' + p.id"
              class="btn btn-warning btn-sm me-1"
            >
              Edit
            </router-link>

            <a
              :href="'/products/delete/' + p.id"
              class="btn btn-danger btn-sm"
            >
              Delete
            </a>

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
      products: [],
    };

  },

  mounted() {

    this.loadProducts();

  },

  methods: {

    loadProducts() {

      fetch('/products-data')

        .then((res) => res.json())

        .then((data) => {

          this.products = data;

        })

        .catch((error) => {

          console.error(
            'Unable to load products:',
            error
          );

        });

    },

    scoreBadge(score) {

      if (score >= 80) {
        return 'bg-success';
      }

      if (score >= 50) {
        return 'bg-warning text-dark';
      }

      return 'bg-danger';
    },

  },

};
</script>