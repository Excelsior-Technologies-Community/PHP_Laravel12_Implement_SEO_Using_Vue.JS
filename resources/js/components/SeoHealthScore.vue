<template>
    <div class="card shadow-sm mt-3">

        <div class="card-header">
            <strong>SEO Health Audit</strong>
        </div>

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-4 text-center">

                    <div class="score-circle mx-auto" :class="scoreClass">
                        <span>{{ score }}</span>
                        <small>/100</small>
                    </div>

                    <h6 class="mt-2">
                        {{ scoreLabel }}
                    </h6>

                </div>

                <div class="col-md-8">

                    <div v-if="issues.length === 1 && score === 100" class="alert alert-success mb-0">
                        <strong>Excellent!</strong>
                        {{ issues[0] }}
                    </div>

                    <div v-else>

                        <div v-for="(issue, index) in issues" :key="index" class="audit-item">

                            <span class="me-2">
                                {{ getIssueIcon(issue) }}
                            </span>

                            <span>{{ issue }}</span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</template>

<script>
export default {
    name: 'SeoHealthScore',

    props: {
        score: {
            type: Number,
            default: 0,
        },

        issues: {
            type: Array,
            default: () => [],
        },
    },

    computed: {

        scoreClass() {

            if (this.score >= 80) {
                return 'score-good';
            }

            if (this.score >= 50) {
                return 'score-warning';
            }

            return 'score-danger';
        },

        scoreLabel() {

            if (this.score >= 80) {
                return 'Good SEO';
            }

            if (this.score >= 50) {
                return 'Needs Improvement';
            }

            return 'Poor SEO';
        },
    },

    methods: {

        getIssueIcon(issue) {

            if (
                issue.startsWith('Great!')
            ) {
                return '✓';
            }

            return '⚠';
        },
    },
};
</script>

<style scoped>
.score-circle {
    width: 120px;
    height: 120px;
    border-radius: 50%;

    display: flex;
    flex-direction: column;

    align-items: center;
    justify-content: center;

    border: 8px solid;
}

.score-circle span {
    font-size: 32px;
    font-weight: 700;
}

.score-circle small {
    font-size: 14px;
}

.score-good {
    border-color: #198754;
    color: #198754;
}

.score-warning {
    border-color: #ffc107;
    color: #997404;
}

.score-danger {
    border-color: #dc3545;
    color: #dc3545;
}

.audit-item {
    padding: 8px 0;

    border-bottom: 1px solid #eee;
}

.audit-item:last-child {
    border-bottom: none;
}
</style>