/* POP UP JS */
(document).ready(function() {
    $('#btn-click').click()
})


/* CODE TEXTILATE */
$( function ( ) {
    $('.tx').textillate({
// //     /* voir plus tard ce que c'est
        minDisplayTime: 2000,

// //     // temps avant que l'animation se lance
        initialDelay: 500, // initialDelay: 2000, avec pop up !

// //     // obligé pour que l'animation se lance directement
        autoStart: true,

// //     // in animation settings
        in: {
// //         // set the effect name
            effect: 'flipInX',

// //       // temps les lettres apparaissent petit a petit
            delayScale: 2,

// //       // pour que la couleur s'affiche directement
            color: true,

            // set the delay between each character
            // delay: 50,

// reverse the character sequence
// (note that reverse doesn't make sense with sync = true)
            sequence: true,

            // callback that executes once the animation has finished
            callback: function () {}
        },

        type: 'char'
    });
});


/* CODE JS POUR ROW SCROLL */

document.querySelector('.scroll-btn').addEventListener('click',() => {
    document.querySelector('html').style.scrollBehavior='smooth';
    setTimeout(()=>{
        document.querySelector('html').style.scrollBehavior='unset';
    },1000)
});
