<script setup>
import Dashboard from './components/Dashboard.vue'; // <--- AJOUT
import ReviewForm from './components/ReviewForm.vue';
import Reviews from './components/Reviews.vue';
</script>

<template>
  <header>
    <h1>Mon Projet Laravel IA</h1>
  </header>

  <main>
    <Dashboard /> 
    
    <ReviewForm /> <Reviews />
  </main>
</template>

<style>
/* Garde ton style existant ici */
body { font-family: sans-serif; background-color: #f8f9fa; margin: 0; }
header { background-color: #3b82f6; color: white; padding: 1rem; text-align: center; }
main { padding: 20px; max-width: 900px; margin: 0 auto; } /* J'ai ajouté un max-width pour centrer le tout */
</style>