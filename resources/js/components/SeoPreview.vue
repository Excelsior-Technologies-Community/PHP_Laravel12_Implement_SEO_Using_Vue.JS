<template>
  <div class="border rounded bg-white p-3 mt-3 seo-preview">

    <div class="border-bottom pb-2 mb-2">
      <h6 class="mb-1 fw-bold text-dark">Google SERP Preview</h6>

      <div class="text-lowercase text-success fw-medium text-truncate">{{ previewUrl }}</div>

      <div class="fw-bold text-primary text-truncate">{{ displayTitle }}</div>

      <div class="text-muted small text-truncate">{{ displayDescription }}</div>
    </div>

    <hr class="my-2">

    <div class="border-bottom pb-2 mb-2">
      <h6 class="mb-1 fw-bold text-dark">Social (OG / Twitter) Preview</h6>

      <div class="border rounded overflow-hidden" style="max-width: 500px;">
        <div class="bg-secondary bg-opacity-25 p-2 small text-uppercase text-muted">Card preview</div>
        <div class="p-2">
          <div class="fw-bold text-truncate">{{ displayOgTitle }}</div>
          <div class="text-muted small text-truncate">{{ displayOgDescription }}</div>
          <img v-if="ogImage" :src="ogImage" alt="OG image" class="img-fluid mt-1 rounded">
        </div>
      </div>
    </div>

    <hr class="my-2">

    <div class="d-flex justify-content-between small">
      <span class="fw-medium">SEO Title:</span>
      <span :class="countClass(titleCount, limits.title)">{{ titleCount }}/{{ limits.title }}</span>
    </div>
    <div class="progress mb-1" style="height: 6px;">
      <div class="progress-bar" :class="countClass(titleCount, limits.title, true)" :style="{ width: titlePercent + '%' }"></div>
    </div>

    <div class="d-flex justify-content-between small">
      <span class="fw-medium">Meta Description:</span>
      <span :class="countClass(descCount, limits.description)">{{ descCount }}/{{ limits.description }}</span>
    </div>
    <div class="progress mb-1" style="height: 6px;">
      <div class="progress-bar" :class="countClass(descCount, limits.description, true)" :style="{ width: descPercent + '%' }"></div>
    </div>

    <div class="d-flex justify-content-between small">
      <span class="fw-medium">OG Title:</span>
      <span :class="countClass(ogTitleCount, limits.ogTitle)">{{ ogTitleCount }}/{{ limits.ogTitle }}</span>
    </div>

    <div class="d-flex justify-content-between small">
      <span class="fw-medium">OG Description:</span>
      <span :class="countClass(ogDescCount, limits.ogDescription)">{{ ogDescCount }}/{{ limits.ogDescription }}</span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SeoPreview',
  props: {
    title: { type: String, default: '' },
    description: { type: String, default: '' },
    ogTitle: { type: String, default: '' },
    ogDescription: { type: String, default: '' },
    ogImage: { type: String, default: '' },
    productUrl: { type: String, default: '' },
  },

  data() {
    return {
      limits: {
        title: 60,
        description: 160,
        ogTitle: 60,
        ogDescription: 110,
      },
    };
  },

  computed: {
    previewUrl() {
      return this.productUrl || 'https://example.com/product/slug';
    },
    displayTitle() {
      return this.title || '(Page title)';
    },
    displayDescription() {
      return this.description || '(No description provided)';
    },
    displayOgTitle() {
      return this.ogTitle || this.title || '(OG title)';
    },
    displayOgDescription() {
      return this.ogDescription || this.description || '(OG description)';
    },
    titleCount() { return this.title.length; },
    descCount() { return this.description.length; },
    ogTitleCount() { return this.ogTitle.length; },
    ogDescCount() { return this.ogDescription.length; },
    titlePercent() { return Math.min((this.titleCount / this.limits.title) * 100, 100); },
    descPercent() { return Math.min((this.descCount / this.limits.description) * 100, 100); },
  },

  methods: {
    countClass(count, limit, isBar = false) {
      if (count === 0) return isBar ? 'bg-secondary' : 'text-muted';
      if (count < limit * 0.8) return isBar ? 'bg-success' : 'text-success';
      if (count < limit) return isBar ? 'bg-warning' : 'text-warning';
      return isBar ? 'bg-danger' : 'text-danger';
    },
  },
};
</script>

<style scoped>
.seo-preview .progress-bar {
  transition: width 0.3s ease;
}
</style>
