<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';

const stats = ref(null);

onMounted(async () => {
    try {
        const response = await api.get('/dashboard');
        stats.value = response.data;
    } catch (error) {
        console.error("Erreur Stats:", error);
    }
});
</script>

<template>
  <div v-if="stats" class="dashboard">
    <div class="stat-card">
        <h3>Note Moyenne</h3>
        <div class="big-number" :class="getScoreColor(stats.average_score)">
            {{ stats.average_score }}<span class="small">/100</span>
        </div>
    </div>

    <div class="stat-card">
        <h3>Total Avis</h3>
        <div class="big-number">{{ stats.total_reviews }}</div>
    </div>

    <div class="stat-card">
        <h3>Top Thèmes</h3>
        <ul v-if="stats.top_topics.length > 0">
            <li v-for="topic in stats.top_topics" :key="topic">#{{ topic }}</li>
        </ul>
        <p v-else class="empty">Pas assez de données</p>
    </div>
  </div>
</template>

<script>
// Petite fonction helper pour la couleur (hors du setup pour être propre)
function getScoreColor(score) {
    if (score >= 70) return 'text-green';
    if (score < 40) return 'text-red';
    return 'text-orange';
}
</script>

<style scoped>
.dashboard {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}
.stat-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    text-align: center;
    border: 1px solid #eee;
}
h3 { margin: 0 0 10px 0; color: #64748b; font-size: 0.9em; text-transform: uppercase; letter-spacing: 1px; }
.big-number { font-size: 2.5em; font-weight: bold; color: #1e293b; }
.small { font-size: 0.4em; color: #94a3b8; }
.text-green { color: #10b981; }
.text-orange { color: #f59e0b; }
.text-red { color: #ef4444; }
ul { list-style: none; padding: 0; }
li { background: #e0f2fe; color: #0369a1; display: inline-block; padding: 5px 10px; border-radius: 15px; margin: 2px; font-size: 0.9em; }
.empty { color: #cbd5e1; font-style: italic; }
</style>