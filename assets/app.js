//import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 * 
 */

// Ajout de la librairie select2
$('.select2').select2({
    selectOnClose: true
});


// Ajout de ck editor pour la description

ClassicEditor
    .create(document.querySelector('#offer_content'))
    .then(editor => {
    })
    .catch(error => {
    });


// 
ClassicEditor
    .create(document.querySelector('#entreprise_profil_description'))
    .then(editor => {
    })
    .catch(error => {
    });



import './styles/app.css';

