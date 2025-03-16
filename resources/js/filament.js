// document.addEventListener('DOMContentLoaded', function () {
//     const collapsibleTriggers = document.querySelectorAll('.fi-collapsible-trigger');

//     collapsibleTriggers.forEach(trigger => {
//         trigger.addEventListener('click', function () {
//             const content = this.nextElementSibling;

//             if (content.style.maxHeight) {
//                 // Collapse
//                 content.style.maxHeight = null;
//                 content.style.opacity = 0;
//             } else {
//                 // Expand
//                 content.style.maxHeight = content.scrollHeight + 'px';
//                 content.style.opacity = 1;
//             }
//         });
//     });
// });