<template>

  <div
    class="container mt-4 mb-5"
    v-if="product"
  >

    <h2>Edit Product</h2>

    <form
      :action="`/products/update/${product.id}`"
      method="POST"
      enctype="multipart/form-data"
      @submit="handleSubmit"
    >

      <input
        type="hidden"
        name="_token"
        :value="csrf"
      >

      <!-- =========================================================
           BASIC DETAILS
      ========================================================== -->

      <label>Name</label>

      <input
        name="name"
        class="form-control mb-2"
        v-model="product.name"
      >

      <label>Slug</label>

      <input
        name="slug"
        class="form-control mb-1"
        v-model="product.slug"
      >

      <div
        class="small mb-2"
        :class="slugValidation.class"
      >
        {{ slugValidation.message }}
      </div>


      <!-- MAIN IMAGE -->

      <label>Current Image</label>

      <br>

      <img
        :src="product.image_url"
        width="80"
        class="mb-2"
        v-if="product.image_url"
      >

      <br>

      <label>Change Image</label>

      <input
        type="file"
        name="image"
        class="form-control mb-2"
      >


      <label>Image Alt Text (SEO)</label>

      <input
        name="alt_text"
        class="form-control mb-1"
        v-model="product.alt_text"
        placeholder="e.g. Red running shoes, side view"
      >

      <div
        class="small mb-2"
        :class="altTextValidation.class"
      >
        {{ altTextValidation.message }}
      </div>


      <label>Size</label>

      <input
        name="size"
        class="form-control mb-2"
        v-model="product.size"
      >


      <label>Price</label>

      <input
        name="price"
        type="number"
        class="form-control mb-2"
        v-model.number="product.price"
      >


      <hr>


      <!-- =========================================================
           SEO DETAILS
      ========================================================== -->

      <h4>SEO & OG Details</h4>


      <!-- SEO IMAGE -->

      <label>Current SEO Image</label>

      <br>

      <img
        :src="product.seo_image_url"
        width="80"
        class="mb-2"
        v-if="product.seo_image_url"
      >

      <br>

      <label>Change SEO Image</label>

      <input
        type="file"
        name="seo_image"
        class="form-control mb-2"
      >


      <label>SEO Image Alt Text</label>

      <input
        name="seo_image_alt"
        class="form-control mb-1"
        v-model="product.seo_image_alt"
      >

      <div
        class="small mb-2"
        :class="seoImageAltValidation.class"
      >
        {{ seoImageAltValidation.message }}
      </div>


      <!-- OG IMAGE -->

      <label>Current OG Image</label>

      <br>

      <img
        :src="product.og_image_url"
        width="80"
        class="mb-2"
        v-if="product.og_image_url"
      >

      <br>

      <label>Change OG Image</label>

      <input
        type="file"
        name="og_image"
        class="form-control mb-2"
      >


      <label>OG Image Alt Text</label>

      <input
        name="og_image_alt"
        class="form-control mb-1"
        v-model="product.og_image_alt"
      >

      <div
        class="small mb-2"
        :class="ogImageAltValidation.class"
      >
        {{ ogImageAltValidation.message }}
      </div>


      <!-- SEO TITLE -->

      <label>SEO Meta Title</label>

      <input
        name="seo_meta_title"
        class="form-control mb-1"
        v-model="product.seo_meta_title"
        maxlength="60"
      >

      <CharacterCounter
        :current="(product.seo_meta_title || '').length"
        :max="60"
        :min="50"
      />


      <!-- OG TITLE -->

      <label class="mt-3">
        OG Meta Title
      </label>

      <input
        name="og_meta_title"
        class="form-control mb-1"
        v-model="product.og_meta_title"
        maxlength="60"
      >

      <CharacterCounter
        :current="(product.og_meta_title || '').length"
        :max="60"
        :min="40"
      />


      <!-- SEO KEYWORDS -->

      <label class="mt-3">
        SEO Meta Keywords
      </label>

      <textarea
        name="seo_meta_keywords"
        class="form-control mb-1"
        v-model="product.seo_meta_keywords"
        rows="3"
      ></textarea>

      <div
        class="small mb-2"
        :class="keywordValidation.class"
      >
        {{ keywordValidation.message }}
      </div>


      <!-- OG KEYWORDS -->

      <label>OG Meta Keywords</label>

      <textarea
        name="og_meta_keywords"
        class="form-control mb-2"
        v-model="product.og_meta_keywords"
        rows="3"
      ></textarea>


      <!-- SEO DESCRIPTION -->

      <label>
        SEO Meta Description
      </label>

      <textarea
        name="seo_meta_description"
        class="form-control mb-1"
        v-model="product.seo_meta_description"
        maxlength="160"
        rows="4"
      ></textarea>

      <CharacterCounter
        :current="(product.seo_meta_description || '').length"
        :max="160"
        :min="120"
      />


      <!-- OG DESCRIPTION -->

      <label class="mt-3">
        OG Meta Description
      </label>

      <textarea
        name="og_meta_description"
        class="form-control mb-1"
        v-model="product.og_meta_description"
        maxlength="110"
        rows="3"
      ></textarea>

      <CharacterCounter
        :current="(product.og_meta_description || '').length"
        :max="110"
        :min="80"
      />


      <!-- CANONICAL -->

      <label class="mt-3">
        SEO Canonical URL
      </label>

      <input
        name="seo_canonical"
        class="form-control mb-1"
        v-model="product.seo_canonical"
      >

      <div
        class="small mb-2"
        :class="canonicalValidation.class"
      >
        {{ canonicalValidation.message }}
      </div>


      <!-- ROBOTS -->

      <label>Meta Robots</label>

      <select
        name="meta_robots"
        class="form-control mb-2"
        v-model="product.meta_robots"
      >

        <option value="index,follow">
          index,follow
        </option>

        <option value="noindex,nofollow">
          noindex,nofollow
        </option>

      </select>


      <!-- =========================================================
           SEO ANALYZER
      ========================================================== -->

      <div class="card mt-4 mb-4">

        <div class="card-header">
          <strong>
            SEO Validation & Character-Length Analyzer
          </strong>
        </div>

        <div class="card-body">

          <!-- TITLE -->

          <div class="mb-3">

            <div class="d-flex justify-content-between">

              <strong>
                SEO Meta Title
              </strong>

              <span :class="titleValidation.class">
                {{ (product.seo_meta_title || '').length }} / 60
              </span>

            </div>

            <div
              class="progress mt-1"
              style="height: 6px;"
            >

              <div
                class="progress-bar"
                :class="titleValidation.progressClass"
                :style="{ width: titleProgress + '%' }"
              ></div>

            </div>

            <small :class="titleValidation.class">
              {{ titleValidation.message }}
            </small>

          </div>


          <!-- DESCRIPTION -->

          <div class="mb-3">

            <div class="d-flex justify-content-between">

              <strong>
                SEO Meta Description
              </strong>

              <span :class="descriptionValidation.class">
                {{ (product.seo_meta_description || '').length }} / 160
              </span>

            </div>

            <div
              class="progress mt-1"
              style="height: 6px;"
            >

              <div
                class="progress-bar"
                :class="descriptionValidation.progressClass"
                :style="{ width: descriptionProgress + '%' }"
              ></div>

            </div>

            <small :class="descriptionValidation.class">
              {{ descriptionValidation.message }}
            </small>

          </div>


          <!-- KEYWORD -->

          <div class="mb-3">

            <strong>
              Focus Keyword
            </strong>

            <div class="mt-1">

              <span
                v-if="focusKeyword"
                class="badge bg-primary"
              >
                {{ focusKeyword }}
              </span>

              <span
                v-else
                class="text-muted"
              >
                No focus keyword detected
              </span>

            </div>


            <div class="mt-2">

              <div
                v-if="keywordInTitle"
                class="text-success"
              >
                ✓ Focus keyword found in SEO title
              </div>

              <div
                v-else
                class="text-warning"
              >
                ⚠ Focus keyword is missing from SEO title
              </div>


              <div
                v-if="keywordInDescription"
                class="text-success"
              >
                ✓ Focus keyword found in SEO description
              </div>

              <div
                v-else
                class="text-warning"
              >
                ⚠ Focus keyword is missing from SEO description
              </div>

            </div>

          </div>


          <!-- SLUG -->

          <div class="mb-3">

            <strong>
              URL / Slug
            </strong>

            <div :class="slugValidation.class">
              {{ slugValidation.message }}
            </div>

          </div>


          <!-- CANONICAL -->

          <div class="mb-3">

            <strong>
              Canonical URL
            </strong>

            <div :class="canonicalValidation.class">
              {{ canonicalValidation.message }}
            </div>

          </div>


          <!-- IMAGE ALT -->

          <div class="mb-3">

            <strong>
              Image Alt Text
            </strong>

            <div :class="altTextValidation.class">
              {{ altTextValidation.message }}
            </div>

          </div>


          <!-- SUMMARY -->

          <div
            class="alert mt-3"
            :class="overallSeoClass"
          >

            <strong>
              SEO Analyzer:
            </strong>

            {{ seoSummary }}

          </div>

        </div>

      </div>


      <!-- =========================================================
           SEO PREVIEW
      ========================================================== -->

      <SeoPreview
        :title="product.seo_meta_title || product.name"
        :description="product.seo_meta_description"
        :og-title="product.og_meta_title || product.name"
        :og-description="product.og_meta_description"
        :og-image="product.og_image_url"
        :product-url="previewUrl"
      />


      <!-- =========================================================
           SEO HEALTH SCORE
      ========================================================== -->

      <SeoHealthScore
        :score="seoScore"
        :issues="seoIssues"
      />


      <!-- BUTTONS -->

      <div class="mt-3">

        <button
          type="submit"
          class="btn btn-primary"
        >
          Update
        </button>

        <router-link
          to="/products"
          class="btn btn-secondary ms-2"
        >
          Back
        </router-link>

      </div>

    </form>

  </div>


  <!-- LOADING -->

  <div
    v-else
    class="container mt-5 text-center"
  >

    <div
      class="spinner-border"
      role="status"
    ></div>

    <p class="mt-2">
      Loading product...
    </p>

  </div>

</template>


<script>

import SeoPreview from '../components/SeoPreview.vue';
import SeoHealthScore from '../components/SeoHealthScore.vue';


const CharacterCounter = {

  props: {

    current: {
      type: Number,
      default: 0,
    },

    max: {
      type: Number,
      required: true,
    },

    min: {
      type: Number,
      default: 0,
    },

  },


  computed: {

    className() {

      if (this.current === 0) {
        return 'text-muted';
      }

      if (this.current > this.max) {
        return 'text-danger';
      }

      if (this.current < this.min) {
        return 'text-warning';
      }

      return 'text-success';

    },


    message() {

      if (this.current === 0) {
        return '⚠ Add content';
      }

      if (this.current > this.max) {
        return `⚠ Too long by ${this.current - this.max} characters`;
      }

      if (this.current < this.min) {
        return `⚠ Recommended: ${this.min}-${this.max} characters`;
      }

      return '✓ Good length';

    },

  },


  template: `
    <div class="small mb-2" :class="className">
      {{ current }} / {{ max }} — {{ message }}
    </div>
  `,

};


export default {

  components: {
    SeoPreview,
    SeoHealthScore,
    CharacterCounter,
  },


  props: ['id'],


  data() {

    return {

      product: null,

    };

  },


  computed: {

    csrf() {

      const element =
        document.querySelector(
          'meta[name=csrf-token]'
        );

      return element
        ? element.content
        : '';

    },


    previewUrl() {

      if (
        this.product &&
        this.product.slug
      ) {

        return `${window.location.origin}/product/${this.product.slug}`;

      }

      return `${window.location.origin}/products`;

    },


    /*
    |--------------------------------------------------------------------------
    | FOCUS KEYWORD
    |--------------------------------------------------------------------------
    */

    focusKeyword() {

      if (!this.product) {
        return '';
      }

      const keywords =
        (this.product.seo_meta_keywords || '')
          .split(',')
          .map(keyword => keyword.trim())
          .filter(Boolean);

      return keywords.length
        ? keywords[0]
        : '';

    },


    keywordInTitle() {

      if (
        !this.focusKeyword ||
        !this.product ||
        !this.product.seo_meta_title
      ) {
        return false;
      }

      return this.product.seo_meta_title
        .toLowerCase()
        .includes(
          this.focusKeyword.toLowerCase()
        );

    },


    keywordInDescription() {

      if (
        !this.focusKeyword ||
        !this.product ||
        !this.product.seo_meta_description
      ) {
        return false;
      }

      return this.product.seo_meta_description
        .toLowerCase()
        .includes(
          this.focusKeyword.toLowerCase()
        );

    },


    /*
    |--------------------------------------------------------------------------
    | TITLE VALIDATION
    |--------------------------------------------------------------------------
    */

    titleValidation() {

      const length =
        (this.product?.seo_meta_title || '').length;

      if (!length) {

        return {
          class: 'text-danger',
          message: '⚠ SEO title is required.',
          progressClass: 'bg-danger',
        };

      }

      if (length < 50) {

        return {
          class: 'text-warning',
          message: '⚠ Title is short. Recommended length is 50-60 characters.',
          progressClass: 'bg-warning',
        };

      }

      if (length > 60) {

        return {
          class: 'text-danger',
          message: '⚠ SEO title exceeds the 60 character limit.',
          progressClass: 'bg-danger',
        };

      }

      return {
        class: 'text-success',
        message: '✓ SEO title length is optimal.',
        progressClass: 'bg-success',
      };

    },


    titleProgress() {

      if (!this.product) {
        return 0;
      }

      return Math.min(
        100,
        ((this.product.seo_meta_title || '').length / 60) * 100
      );

    },


    /*
    |--------------------------------------------------------------------------
    | DESCRIPTION VALIDATION
    |--------------------------------------------------------------------------
    */

    descriptionValidation() {

      const length =
        (this.product?.seo_meta_description || '').length;

      if (!length) {

        return {
          class: 'text-danger',
          message: '⚠ SEO description is required.',
          progressClass: 'bg-danger',
        };

      }

      if (length < 120) {

        return {
          class: 'text-warning',
          message: '⚠ Description is short. Recommended length is 120-160 characters.',
          progressClass: 'bg-warning',
        };

      }

      if (length > 160) {

        return {
          class: 'text-danger',
          message: '⚠ SEO description exceeds the 160 character limit.',
          progressClass: 'bg-danger',
        };

      }

      return {
        class: 'text-success',
        message: '✓ SEO description length is optimal.',
        progressClass: 'bg-success',
      };

    },


    descriptionProgress() {

      if (!this.product) {
        return 0;
      }

      return Math.min(
        100,
        ((this.product.seo_meta_description || '').length / 160) * 100
      );

    },


    /*
    |--------------------------------------------------------------------------
    | KEYWORD VALIDATION
    |--------------------------------------------------------------------------
    */

    keywordValidation() {

      if (!this.product) {

        return {
          class: 'text-muted',
          message: '',
        };

      }

      if (
        !(this.product.seo_meta_keywords || '').trim()
      ) {

        return {
          class: 'text-danger',
          message: '⚠ Add at least one SEO keyword.',
        };

      }

      if (!this.keywordInTitle) {

        return {
          class: 'text-warning',
          message: '⚠ Focus keyword is not present in the SEO title.',
        };

      }

      if (!this.keywordInDescription) {

        return {
          class: 'text-warning',
          message: '⚠ Focus keyword is not present in the SEO description.',
        };

      }

      return {
        class: 'text-success',
        message: '✓ Focus keyword is used correctly.',
      };

    },


    /*
    |--------------------------------------------------------------------------
    | SLUG VALIDATION
    |--------------------------------------------------------------------------
    */

    slugValidation() {

      if (!this.product) {

        return {
          class: 'text-muted',
          message: '',
        };

      }

      const slug =
        (this.product.slug || '').trim();

      if (!slug) {

        return {
          class: 'text-danger',
          message: '⚠ SEO-friendly slug is required.',
        };

      }

      if (!/^[a-z0-9]+(?:-[a-z0-9]+)*$/.test(slug)) {

        return {
          class: 'text-danger',
          message: '⚠ Slug should contain lowercase letters, numbers and hyphens only.',
        };

      }

      if (
        slug.startsWith('-') ||
        slug.endsWith('-') ||
        slug.includes('--')
      ) {

        return {
          class: 'text-danger',
          message: '⚠ Slug contains invalid hyphen formatting.',
        };

      }

      return {
        class: 'text-success',
        message: '✓ SEO-friendly slug.',
      };

    },


    /*
    |--------------------------------------------------------------------------
    | CANONICAL URL
    |--------------------------------------------------------------------------
    */

    canonicalValidation() {

      if (!this.product) {

        return {
          class: 'text-muted',
          message: '',
        };

      }

      const value =
        (this.product.seo_canonical || '').trim();

      if (!value) {

        return {
          class: 'text-danger',
          message: '⚠ Canonical URL is required.',
        };

      }

      try {

        const url = new URL(value);

        if (
          !['http:', 'https:']
            .includes(url.protocol)
        ) {
          throw new Error();
        }

        return {
          class: 'text-success',
          message: '✓ Valid canonical URL.',
        };

      } catch (error) {

        return {
          class: 'text-danger',
          message: '⚠ Enter a valid HTTP/HTTPS canonical URL.',
        };

      }

    },


    /*
    |--------------------------------------------------------------------------
    | IMAGE ALT
    |--------------------------------------------------------------------------
    */

    altTextValidation() {

      if (!this.product) {

        return {
          class: 'text-muted',
          message: '',
        };

      }

      const value =
        (this.product.alt_text || '').trim();

      if (!value) {

        return {
          class: 'text-danger',
          message: '⚠ Main image alt text is required.',
        };

      }

      if (value.length < 5) {

        return {
          class: 'text-warning',
          message: '⚠ Alt text is too short.',
        };

      }

      return {
        class: 'text-success',
        message: '✓ Main image alt text is provided.',
      };

    },


    seoImageAltValidation() {

      if (!this.product) {

        return {
          class: 'text-muted',
          message: '',
        };

      }

      const value =
        (this.product.seo_image_alt || '').trim();

      if (!value) {

        return {
          class: 'text-warning',
          message: '⚠ SEO image alt text is recommended.',
        };

      }

      return {
        class: 'text-success',
        message: '✓ SEO image alt text is provided.',
      };

    },


    ogImageAltValidation() {

      if (!this.product) {

        return {
          class: 'text-muted',
          message: '',
        };

      }

      const value =
        (this.product.og_image_alt || '').trim();

      if (!value) {

        return {
          class: 'text-warning',
          message: '⚠ OG image alt text is recommended.',
        };

      }

      return {
        class: 'text-success',
        message: '✓ OG image alt text is provided.',
      };

    },


    /*
    |--------------------------------------------------------------------------
    | SEO SCORE
    |--------------------------------------------------------------------------
    */

    seoScore() {

      if (!this.product) {
        return 0;
      }

      const checks = [

        this.product.seo_meta_title &&
        this.product.seo_meta_title.length >= 50 &&
        this.product.seo_meta_title.length <= 60,

        this.product.seo_meta_description &&
        this.product.seo_meta_description.length >= 120 &&
        this.product.seo_meta_description.length <= 160,

        (this.product.seo_meta_keywords || '').trim(),

        this.keywordInTitle,

        this.keywordInDescription,

        this.product.seo_canonical &&
        this.canonicalValidation.class === 'text-success',

        this.product.slug &&
        this.slugValidation.class === 'text-success',

        (this.product.alt_text || '').trim(),

        (this.product.seo_image_alt || '').trim(),

        (this.product.og_image_alt || '').trim(),

        this.product.og_meta_title &&
        this.product.og_meta_title.length <= 60,

        this.product.og_meta_description &&
        this.product.og_meta_description.length <= 110,

      ];

      const passed =
        checks.filter(Boolean).length;

      return Math.round(
        (passed / checks.length) * 100
      );

    },


    /*
    |--------------------------------------------------------------------------
    | SEO ISSUES
    |--------------------------------------------------------------------------
    */

    seoIssues() {

      if (!this.product) {
        return [];
      }

      const issues = [];


      if (!this.product.seo_meta_title) {

        issues.push(
          'Add an SEO meta title.'
        );

      } else if (
        this.product.seo_meta_title.length < 50
      ) {

        issues.push(
          'SEO meta title is shorter than the recommended 50 characters.'
        );

      } else if (
        this.product.seo_meta_title.length > 60
      ) {

        issues.push(
          'SEO meta title exceeds 60 characters.'
        );

      }


      if (!this.product.seo_meta_description) {

        issues.push(
          'Add an SEO meta description.'
        );

      } else if (
        this.product.seo_meta_description.length < 120
      ) {

        issues.push(
          'SEO meta description is shorter than the recommended 120 characters.'
        );

      } else if (
        this.product.seo_meta_description.length > 160
      ) {

        issues.push(
          'SEO meta description exceeds 160 characters.'
        );

      }


      if (
        !(this.product.seo_meta_keywords || '').trim()
      ) {

        issues.push(
          'Add SEO keywords.'
        );

      }


      if (
        this.focusKeyword &&
        !this.keywordInTitle
      ) {

        issues.push(
          'Focus keyword is missing from the SEO title.'
        );

      }


      if (
        this.focusKeyword &&
        !this.keywordInDescription
      ) {

        issues.push(
          'Focus keyword is missing from the SEO description.'
        );

      }


      if (!this.product.seo_canonical) {

        issues.push(
          'Add a canonical URL.'
        );

      } else if (
        this.canonicalValidation.class !== 'text-success'
      ) {

        issues.push(
          'Canonical URL is invalid.'
        );

      }


      if (!this.product.slug) {

        issues.push(
          'Add an SEO-friendly slug.'
        );

      } else if (
        this.slugValidation.class !== 'text-success'
      ) {

        issues.push(
          'Slug contains invalid characters.'
        );

      }


      if (
        !(this.product.alt_text || '').trim()
      ) {

        issues.push(
          'Add alt text to the main product image.'
        );

      }


      if (!this.product.og_meta_title) {

        issues.push(
          'Add an Open Graph title.'
        );

      }


      if (!this.product.og_meta_description) {

        issues.push(
          'Add an Open Graph description.'
        );

      }


      if (!issues.length) {

        issues.push(
          'Great! No major SEO issues were found.'
        );

      }

      return issues;

    },


    seoSummary() {

      if (this.seoScore >= 90) {

        return 'Excellent! Your product SEO is well optimized.';

      }

      if (this.seoScore >= 70) {

        return 'Good SEO setup. Consider fixing the remaining warnings.';

      }

      if (this.seoScore >= 50) {

        return 'Some SEO improvements are recommended.';

      }

      return 'Several SEO improvements are required.';

    },


    overallSeoClass() {

      if (this.seoScore >= 90) {
        return 'alert-success';
      }

      if (this.seoScore >= 70) {
        return 'alert-info';
      }

      if (this.seoScore >= 50) {
        return 'alert-warning';
      }

      return 'alert-danger';

    },

  },


  mounted() {

    fetch(`/product-data/${this.id}`, {
      headers: {
        'Accept': 'application/json',
      },
    })

      .then(response => {

        if (!response.ok) {
          throw new Error(
            `HTTP ${response.status}`
          );
        }

        return response.json();

      })

      .then(data => {

        this.product = data;

      })

      .catch(error => {

        console.error(
          'Failed to load product:',
          error
        );

      });

  },


  methods: {

    handleSubmit(event) {

      if (this.seoScore < 50) {

        const confirmed = window.confirm(
          'Your SEO score is below 50. Some important SEO fields need improvement. Do you want to continue updating?'
        );

        if (!confirmed) {
          event.preventDefault();
        }

      }

    },

  },

};

</script>

