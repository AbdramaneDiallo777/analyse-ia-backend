<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';

const reviews = ref([]);
const loading = ref(true);

onMounted(async () => {
    try {
        const response = await api.get('/reviews');
        reviews.value = response.data;
    } catch (error) {
        console.error("Erreur API:", error);
    } finally {
        loading.value = false;
    }
});
</script>

<template>
  <div class="container">
    <h2>💬 Avis Clients</h2>
    <div v-if="loading">Chargement...</div>
    <div v-else-if="reviews.length === 0">Aucun avis pour le moment.</div>
    <div v-else class="grid">
      <div v-for="review in reviews" :key="review.id" class="card">
        <p class="content">"{{ review.content }}"</p>
        <div class="badges">
          <span :class="['badge', review.sentiment]">{{ review.sentiment }}</span>
          <span class="score">Score: {{ review.score }}/100</span>
        </div>
        <div class="topics">
          <span v-for="topic in review.topics" :key="topic" class="topic-tag">#{{ topic }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Force la couleur du texte en noir pour tout le composant */
.container { 
    max-width: 800px; 
    margin: 0 auto; 
    padding: 20px; 
    color: #333333; /* Gris foncé force */
}

h2 {
    color: #2c3e50;
    text-align: center;
    margin-bottom: 20px;
}

.card { 
    border: 1px solid #ddd; 
    padding: 15px; 
    margin-bottom: 15px; 
    border-radius: 8px; 
    background: white; 
    box-shadow: 0 2px 4px rgba(0,0,0,0.05); 
}

.content { font-size: 1.1em; font-style: italic; color: #333; }

.badges { margin-top: 10px; display: flex; gap: 10px; align-items: center; }

.badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; text-transform: uppercase; font-size: 0.8em; }
.badge.positive { background-color: #d1fae5; color: #065f46; }
.badge.neutral { background-color: #f3f4f6; color: #1f2937; }
.badge.negative { background-color: #fee2e2; color: #991b1b; }

.topic-tag { background-color: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 10px; font-size: 0.8em; margin-right: 5px; }
</style>