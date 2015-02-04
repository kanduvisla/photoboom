var Boom = function(){
    "use strict";
    
    // Reference to root:
    var _this = this;
    
    // Center svg: 
    var svg   = document.querySelector('#svg');
    svg.style.marginLeft = (svg.offsetWidth / 2) * -1;
    svg.style.marginTop = (svg.offsetHeight / 2) * -1;
    
    // Scale:
    this.scaleToAvailableSpace = function()
    {
        var availableSpaceX = window.innerWidth - 500;
        var availableSpaceY = window.innerHeight - 200;        
    };
    this.scaleToAvailableSpace();
    
};

// autostart:
new Boom();