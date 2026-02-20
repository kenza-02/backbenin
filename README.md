Guide Intégration API Mobile Citizen SN 

 API accessible via :
   Développement : http://127.0.0.1:8000/api

POINTS D'ACCÈS : 
 Points d’accès GET pour consultation, téléchargement ou streaming
Catégories
Lister toutes les catégories :
GET http://127.0.0.1:8000/api/categories
Voir une catégorie :
GET http://127.0.0.1:8000/api/categories/{id}
Créer une catégorie :
POST http://127.0.0.1:8000/api/categories
Supprimer une catégorie :
DELETE http://127.0.0.1:8000/api/categories/{id}
Documents
Lister tous les documents :
GET http://127.0.0.1:8000/api/documents
Voir un document :
GET http://127.0.0.1:8000/api/documents/{id}
Créer un document :
POST http://127.0.0.1:8000/api/documents
Supprimer un document :
DELETE http://127.0.0.1:8000/api/documents/{id}
Événements
Lister tous les événements :
GET http://127.0.0.1:8000/api/evenements
Voir un événement :
GET http://127.0.0.1:8000/api/evenements/{id}
Créer un événement :
POST http://127.0.0.1:8000/api/evenements
Supprimer un événement :
DELETE http://127.0.0.1:8000/api/evenements/{id}
Intervenants
Lister tous les intervenants :
GET http://127.0.0.1:8000/api/intervenants
Voir un intervenant :
GET http://127.0.0.1:8000/api/intervenants/{id}
Créer un intervenant :
POST http://127.0.0.1:8000/api/intervenants
Supprimer un intervenant :
DELETE http://127.0.0.1:8000/api/intervenants/{id}
Evenement_intervenant
Lister tous les intervenants :
GET http://127.0.0.1:8000/api/evenement-intervenants
Membres
Lister tous les membres :
GET http://127.0.0.1:8000/api/membres
Voir un membre :
GET http://127.0.0.1:8000/api/membres/{id}
Créer un membre :
POST http://127.0.0.1:8000/api/membres
Mettre à jour un membre :
PUT http://127.0.0.1:8000/api/membres/{id}
Supprimer un membre :
DELETE http://127.0.0.1:8000/api/membres/{id}
Podcasts
Lister tous les podcasts :
GET http://127.0.0.1:8000/api/podcasts
Voir un podcast :
GET http://127.0.0.1:8000/api/podcasts/{id}
Créer un podcast :
POST http://127.0.0.1:8000/api/podcasts
Mettre à jour un podcast :
PUT http://127.0.0.1:8000/api/podcasts/{id}
Supprimer un podcast :
DELETE http://127.0.0.1:8000/api/podcasts/{id}
Télécharger un podcast :
GET http://127.0.0.1:8000/api/podcasts/{id}/download
Streamer un podcast :
GET http://127.0.0.1:8000/api/podcasts/{id}/stream
Récupère les 8 derniers podcasts
GET http://127.0.0.0:8000/api/lastpodcasts
Dashboard
Obtenir toutes les données pour le dashboard :
GET http://127.0.0.1:8000/api/dashboard



CONFIGURATION DE BASE :
URL de base
const API_BASE_URL = 'http://127.0.0.1:8000/api';
Fonction générique pour appeler l’API
async function apiCall(endpoint, options = {}) {
   const url = `${API_BASE_URL}${endpoint}`;
   if (options.body instanceof FormData) delete options.headers?.['Content-Type'];
   const response = await fetch(url, options);
   if (!response.ok) {
 	const error = await response.json().catch(() => ({ message: response.statusText }));
 	throw new Error(error.message || `Erreur ${response.status}`);
   }
   return response.json();
 }
Gestion des formulaires simplifiée:
·       Pas d’attribut action sur <form>
·       onsubmit + event.preventDefault() obligatoire
·       Les données sont créées directement dans la base après validation
Exemple : créer un membre directement
<form onsubmit="handleFormSubmit(event, '/api/membres')" enctype="multipart/form-data">
   <input type="text" name="prenom" required placeholder="Prénom">
   <input type="text" name="nom" required placeholder="Nom">
   <input type="email" name="email" placeholder="Email">
   <button type="submit">Créer membre</button>
 </form>
Fonction JavaScript pour soumission
async function handleFormSubmit(event, endpoint) {
   event.preventDefault();
   const form = event.target;
   const submitButton = form.querySelector('button[type="submit"]');
   submitButton.disabled = true;
   const originalText = submitButton.textContent;
   submitButton.textContent = 'En cours...';

   try {
 	const formData = new FormData(form);
 	const method = form.querySelector('input[name="_method"]')?.value || 'POST';
 	const data = await apiCall(endpoint, { method, body: formData });
 	alert('Création réussie !');
 	form.reset();
 	return data;
   } catch (err) {
 	alert(`Erreur : ${err.message}`);
   } finally {
 	submitButton.disabled = false;
 	submitButton.textContent = originalText;
   }
 }
Upload de fichiers:
·       enctype="multipart/form-data" obligatoire
·       Champ fichier pour documents/podcasts, image pour événements
·       Formats et tailles à respecter
Exemple upload document
<form onsubmit="handleFormSubmit(event, '/api/documents')" enctype="multipart/form-data">
   <input type="text" name="libelle" required>
   <input type="file" name="fichier" required>
   <select name="categorie" required>
 	<option value="1">Formation</option>
   </select>
   <button type="submit">Uploader</button>
 </form>




