var Boom = function(){
    "use strict";
    
    // Reference to root:
    var _this = this;
    
    // Center svg: 
    var svg   = document.querySelector('#svg');
    svg.style.marginLeft = (svg.offsetWidth / 2) * -1;
    svg.style.marginTop = (svg.offsetHeight / 2) * -1;
};

// autostart:
new Boom();