<template>

  <div class="container mt-4 mb-5">

    <!-- =========================================================
         HEADER
    ========================================================== -->

    <div class="d-flex justify-content-between align-items-center mb-4">

      <h2>
        Product Management
      </h2>

      <router-link
        to="/products/create"
        class="btn btn-primary"
      >
        + Add Product
      </router-link>

    </div>


    <!-- =========================================================
         STATISTICS
    ========================================================== -->

    <div class="row mb-4">

      <div class="col-md-3 mb-3">

        <div class="card shadow-sm">

          <div class="card-body">

            <h6 class="text-muted">
              Total Products
            </h6>

            <h3>
              {{ statistics.total_products }}
            </h3>

          </div>

        </div>

      </div>


      <div class="col-md-3 mb-3">

        <div class="card shadow-sm">

          <div class="card-body">

            <h6 class="text-muted">
              Average Price
            </h6>

            <h3>
              ₹{{ statistics.average_price }}
            </h3>

          </div>

        </div>

      </div>


      <div class="col-md-3 mb-3">

        <div class="card shadow-sm">

          <div class="card-body">

            <h6 class="text-muted">
              Highest Price
            </h6>

            <h3>
              ₹{{ statistics.highest_price }}
            </h3>

          </div>

        </div>

      </div>


      <div class="col-md-3 mb-3">

        <div class="card shadow-sm">

          <div class="card-body">

            <h6 class="text-muted">
              Lowest Price
            </h6>

            <h3>
              ₹{{ statistics.lowest_price }}
            </h3>

          </div>

        </div>

      </div>

    </div>


    <!-- =========================================================
         SEARCH + FILTERS
    ========================================================== -->

    <div class="card shadow-sm mb-4">

      <div class="card-body">

        <div class="row g-3">

          <!-- SEARCH -->

          <div class="col-md-4">

            <label class="form-label">
              Search
            </label>

            <input
              type="text"
              class="form-control"
              v-model="filters.search"
              placeholder="Search name, slug or size..."
              @input="searchProducts"
            >

          </div>


          <!-- MIN PRICE -->

          <div class="col-md-2">

            <label class="form-label">
              Min Price
            </label>

            <input
              type="number"
              min="0"
              class="form-control"
              v-model="filters.min_price"
              @change="loadProducts"
              placeholder="₹ Min"
            >

          </div>


          <!-- MAX PRICE -->

          <div class="col-md-2">

            <label class="form-label">
              Max Price
            </label>

            <input
              type="number"
              min="0"
              class="form-control"
              v-model="filters.max_price"
              @change="loadProducts"
              placeholder="₹ Max"
            >

          </div>


          <!-- SEO STATUS -->

          <div class="col-md-2">

            <label class="form-label">
              SEO Status
            </label>

            <select
              class="form-select"
              v-model="filters.seo_status"
              @change="loadProducts"
            >

              <option value="">
                All
              </option>

              <option value="excellent">
                Excellent
              </option>

              <option value="good">
                Good
              </option>

              <option value="needs_improvement">
                Needs Improvement
              </option>

            </select>

          </div>


          <!-- SORT -->

          <div class="col-md-2">

            <label class="form-label">
              Sort By
            </label>

            <select
              class="form-select"
              v-model="filters.sort"
              @change="changeSort"
            >

              <option value="newest">
                Newest
              </option>

              <option value="oldest">
                Oldest
              </option>

              <option value="name_asc">
                Name A-Z
              </option>

              <option value="name_desc">
                Name Z-A
              </option>

              <option value="price_asc">
                Price Low-High
              </option>

              <option value="price_desc">
                Price High-Low
              </option>

            </select>

          </div>

        </div>


        <!-- RESET -->

        <div class="mt-3">

          <button
            class="btn btn-secondary btn-sm"
            @click="resetFilters"
          >
            Reset Filters
          </button>

        </div>

      </div>

    </div>


    <!-- =========================================================
         RESULTS INFORMATION
    ========================================================== -->

    <div class="d-flex justify-content-between align-items-center mb-3">

      <div>

        <strong>
          Products:
        </strong>

        {{ pagination.total }}

      </div>


      <div>

        <select
          class="form-select form-select-sm"
          v-model="filters.per_page"
          @change="loadProducts"
        >

          <option :value="5">
            5 per page
          </option>

          <option :value="10">
            10 per page
          </option>

          <option :value="20">
            20 per page
          </option>

          <option :value="50">
            50 per page
          </option>

        </select>

      </div>

    </div>


    <!-- =========================================================
         TABLE
    ========================================================== -->

    <div class="table-responsive">

      <table
        class="table table-bordered table-hover align-middle"
      >

        <thead>

          <tr>

            <th>
              #
            </th>

            <th>
              Name
            </th>

            <th>
              Image
            </th>

            <th>
              Size
            </th>

            <th>
              Price
            </th>

            <th>
              SEO Score
            </th>

            <th>
              Action
            </th>

          </tr>

        </thead>


        <tbody>

          <tr
            v-for="(p, index) in products"
            :key="p.id"
          >

            <td>
              {{
                ((pagination.current_page - 1)
                * pagination.per_page)
                + index
                + 1
              }}
            </td>


            <td>

              <strong>
                {{ p.name }}
              </strong>

              <br>

              <small class="text-muted">
                {{ p.slug }}
              </small>

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

              <br>

              <small>
                {{ seoStatus(p.seo_score) }}
              </small>

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
                @click="confirmDelete"
              >
                Delete
              </a>

            </td>

          </tr>


          <tr v-if="!loading && products.length === 0">

            <td
              colspan="7"
              class="text-center py-4"
            >
              No products found.
            </td>

          </tr>


          <tr v-if="loading">

            <td
              colspan="7"
              class="text-center py-4"
            >

              <div
                class="spinner-border"
                role="status"
              ></div>

              <div class="mt-2">
                Loading products...
              </div>

            </td>

          </tr>

        </tbody>

      </table>

    </div>


    <!-- =========================================================
         PAGINATION
    ========================================================== -->

    <div
      v-if="pagination.last_page > 1"
      class="d-flex justify-content-center mt-4"
    >

      <nav>

        <ul class="pagination">

          <li
            class="page-item"
            :class="{
              disabled: pagination.current_page === 1
            }"
          >

            <button
              class="page-link"
              @click="changePage(pagination.current_page - 1)"
              :disabled="pagination.current_page === 1"
            >
              Previous
            </button>

          </li>


          <li
            v-for="page in visiblePages"
            :key="page"
            class="page-item"
            :class="{
              active: page === pagination.current_page
            }"
          >

            <button
              class="page-link"
              @click="changePage(page)"
            >
              {{ page }}
            </button>

          </li>


          <li
            class="page-item"
            :class="{
              disabled:
                pagination.current_page === pagination.last_page
            }"
          >

            <button
              class="page-link"
              @click="changePage(pagination.current_page + 1)"
              :disabled="
                pagination.current_page === pagination.last_page
              "
            >
              Next
            </button>

          </li>

        </ul>

      </nav>

    </div>

  </div>

</template>


<script>

export default {

  data() {

    return {

      products: [],

      loading: false,

      statistics: {
        total_products: 0,
        average_price: 0,
        highest_price: 0,
        lowest_price: 0,
      },

      filters: {

        search: '',

        min_price: '',

        max_price: '',

        seo_status: '',

        sort: 'newest',

        per_page: 10,

        page: 1,

      },

      pagination: {

        current_page: 1,

        last_page: 1,

        per_page: 10,

        total: 0,

      },

    };

  },


  computed: {

    /*
    |--------------------------------------------------------------------------
    | PAGINATION NUMBERS
    |--------------------------------------------------------------------------
    */

    visiblePages() {

      const pages = [];

      const current = this.pagination.current_page;

      const last = this.pagination.last_page;

      let start = Math.max(1, current - 2);

      let end = Math.min(last, current + 2);

      for (let i = start; i <= end; i++) {
        pages.push(i);
      }

      return pages;

    },

  },


  mounted() {

    this.loadProducts();

    this.loadStatistics();

  },


  methods: {

    /*
    |--------------------------------------------------------------------------
    | LOAD PRODUCTS
    |--------------------------------------------------------------------------
    */

    loadProducts(page = 1) {

      this.loading = true;

      this.filters.page = page;

      const params = new URLSearchParams();

      if (this.filters.search) {
        params.append(
          'search',
          this.filters.search
        );
      }

      if (this.filters.min_price !== '') {
        params.append(
          'min_price',
          this.filters.min_price
        );
      }

      if (this.filters.max_price !== '') {
        params.append(
          'max_price',
          this.filters.max_price
        );
      }

      if (this.filters.seo_status) {
        params.append(
          'seo_status',
          this.filters.seo_status
        );
      }


      /*
      | SORT
      */

      let sortBy = 'created_at';

      let sortOrder = 'desc';

      switch (this.filters.sort) {

        case 'name_asc':

          sortBy = 'name';
          sortOrder = 'asc';

          break;

        case 'name_desc':

          sortBy = 'name';
          sortOrder = 'desc';

          break;

        case 'price_asc':

          sortBy = 'price';
          sortOrder = 'asc';

          break;

        case 'price_desc':

          sortBy = 'price';
          sortOrder = 'desc';

          break;

        case 'oldest':

          sortBy = 'created_at';
          sortOrder = 'asc';

          break;

        default:

          sortBy = 'created_at';
          sortOrder = 'desc';

      }


      params.append(
        'sort_by',
        sortBy
      );

      params.append(
        'sort_order',
        sortOrder
      );

      params.append(
        'per_page',
        this.filters.per_page
      );

      params.append(
        'page',
        page
      );


      fetch(
        `/products-data?${params.toString()}`,
        {
          headers: {
            Accept: 'application/json',
          },
        }
      )

        .then(response => {

          if (!response.ok) {
            throw new Error(
              `HTTP ${response.status}`
            );
          }

          return response.json();

        })

        .then(data => {

          this.products =
            data.data || [];

          this.pagination = {

            current_page:
              data.current_page || 1,

            last_page:
              data.last_page || 1,

            per_page:
              data.per_page || this.filters.per_page,

            total:
              data.total || 0,

          };

        })

        .catch(error => {

          console.error(
            'Unable to load products:',
            error
          );

          this.products = [];

        })

        .finally(() => {

          this.loading = false;

        });

    },


    /*
    |--------------------------------------------------------------------------
    | STATISTICS
    |--------------------------------------------------------------------------
    */

    loadStatistics() {

      fetch('/products-statistics', {
        headers: {
          Accept: 'application/json',
        },
      })

        .then(response => response.json())

        .then(data => {

          this.statistics = data;

        })

        .catch(error => {

          console.error(
            'Unable to load statistics:',
            error
          );

        });

    },


    /*
    |--------------------------------------------------------------------------
    | LIVE SEARCH
    |--------------------------------------------------------------------------
    */

    searchProducts() {

      clearTimeout(this.searchTimer);

      this.searchTimer = setTimeout(() => {

        this.loadProducts(1);

      }, 300);

    },


    /*
    |--------------------------------------------------------------------------
    | SORT
    |--------------------------------------------------------------------------
    */

    changeSort() {

      this.loadProducts(1);

    },


    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */

    changePage(page) {

      if (
        page < 1 ||
        page > this.pagination.last_page
      ) {
        return;
      }

      this.loadProducts(page);

      window.scrollTo({
        top: 0,
        behavior: 'smooth',
      });

    },


    /*
    |--------------------------------------------------------------------------
    | RESET FILTERS
    |--------------------------------------------------------------------------
    */

    resetFilters() {

      this.filters = {

        search: '',

        min_price: '',

        max_price: '',

        seo_status: '',

        sort: 'newest',

        per_page: 10,

        page: 1,

      };

      this.loadProducts(1);

    },


    /*
    |--------------------------------------------------------------------------
    | SEO BADGE
    |--------------------------------------------------------------------------
    */

    scoreBadge(score) {

      if (score >= 90) {
        return 'bg-success';
      }

      if (score >= 70) {
        return 'bg-info text-dark';
      }

      if (score >= 50) {
        return 'bg-warning text-dark';
      }

      return 'bg-danger';

    },


    /*
    |--------------------------------------------------------------------------
    | SEO STATUS
    |--------------------------------------------------------------------------
    */

    seoStatus(score) {

      if (score >= 90) {
        return 'Excellent';
      }

      if (score >= 70) {
        return 'Good';
      }

      return 'Needs Improvement';

    },


    /*
    |--------------------------------------------------------------------------
    | DELETE CONFIRMATION
    |--------------------------------------------------------------------------
    */

    confirmDelete(event) {

      if (
        !window.confirm(
          'Are you sure you want to delete this product?'
        )
      ) {

        event.preventDefault();

      }

    },

  },

};

</script>