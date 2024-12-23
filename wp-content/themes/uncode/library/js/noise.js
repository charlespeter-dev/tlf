(function($) {
	"use strict";

	UNCODE.animatedBgGradient = function( _el ) {

    var ev;

	if ( typeof _el === 'undefined' || _el === null ) {
        _el = document;
        ev = false;
	} else {
        _el = _el[0];
        ev = 'shortcode:update';
    }

    var simplex = new SimplexNoise();

    var Uncode_BG_Animated_Gradient = function(blockOverlay, canvasWrap) {
		this.block = blockOverlay;
		this.wrap = canvasWrap;
		this.config();
		this.checker();
		this.loopRAF();
        this.animLoader();
    };

    var AnimGradient = Uncode_BG_Animated_Gradient.prototype;

    AnimGradient.config = function() {
        this.canvas = document.createElement('canvas');
        this.wrap.appendChild(this.canvas);
        this.ctx = this.canvas.getContext('2d');
        this.count = 0;        
        this.isOrientationChanged = false;
        this.res = UNCODE.isMobile ? 90 : 110;
        this.resPercent = 100/this.res;
        this.resAround = this.resPercent/100;
        this.size = typeof this.block.getAttribute('data-bg-noise-size') !== 'undefined' && this.block.getAttribute('data-bg-noise-size') !== null && this.block.getAttribute('data-bg-noise-size') !== '' ? this.block.getAttribute('data-bg-noise-size') : 1;

        this.canvas.classList.add('uncode-bg-animated-gradient');
        this.canvas.setAttribute('height', this.res);
        this.canvas.setAttribute('width', this.res);
        this.canvas.style = 'height: 100%; position: relative; width:100%;';

        this.imgdata = this.ctx.getImageData(0, 0, this.canvas.width, this.canvas.height);

        this.cx = this.canvas.width/2;
        this.cy = this.canvas.height/2;
        this.data = this.imgdata.data;

        this.opts = {
            col_1st: typeof this.block.getAttribute('data-bg-noise-1') !== 'undefined' && this.block.getAttribute('data-bg-noise-1') !== null && this.block.getAttribute('data-bg-noise-1') !== '' ? this.block.getAttribute('data-bg-noise-1') : false,
            col_2nd: typeof this.block.getAttribute('data-bg-noise-2') !== 'undefined' && this.block.getAttribute('data-bg-noise-2') !== null && this.block.getAttribute('data-bg-noise-2') !== '' ? this.block.getAttribute('data-bg-noise-2') : false,
            time: typeof this.block.getAttribute('data-bg-noise-speed') !== 'undefined' && this.block.getAttribute('data-bg-noise-speed') !== null && this.block.getAttribute('data-bg-noise-speed') !== '' ? parseFloat(this.block.getAttribute('data-bg-noise-speed')) : 250,//100,250,1500
        };

        this.lavaTime = 1;
        this.bg_colors = [];

        if ( this.opts.col_1st === false && this.opts.col_2nd === false ) {
            return;
        }

        if( this.opts.col_1st === this.opts.col_2nd || this.opts.col_2nd == false ) {
            this.singleColor = true;
        }

        if( this.opts.col_1st ) {

            var rgbColor1 = this.hexToRGB(this.opts.col_1st);

            this.bg_colors.push({
                r: rgbColor1.r,
                g: rgbColor1.g,
                b: rgbColor1.b
            });
        }

        if( this.opts.col_2nd ) {

            var rgbColor2 = this.hexToRGB(this.opts.col_2nd);

            this.bg_colors.push({
                r: rgbColor2.r,
                g: rgbColor2.g,
                b: rgbColor2.b
            });
        }

    };

    AnimGradient.checker = function() {
        var scope = this;
        this.checkInViewPort();
      
        window.addEventListener('resize', function() {
            if(UNCODE.isMobile && !scope.isOrientationChanged) {
                return;
            }
            scope.ratioCalc();
        });

        window.addEventListener("orientationchange", function() {
            scope.isOrientationChanged = true;
        });

        this.ratioCalc();
    }; 

    AnimGradient.ratioCalc = function() {
      
        var blockW = this.block.clientWidth,
            blockH = this.block.clientHeight;

        var blockRatio = blockH/blockW;

        if(blockRatio < 1) {
            this.ratioParam = {
                x: 1.4,
                y: blockRatio*1.4
            }
        } else {
            this.ratioParam = {
                x: blockRatio/3,
                y: 1
            }
        }
         
    };

    AnimGradient.checkInViewPort = function() {
		if( 'IntersectionObserver' in window ) {
		    var scope = this,
                observer = new IntersectionObserver(function(entries) {
  
                entries.forEach(function(entry){
                    if ( entry.isIntersecting ) {
                        scope.isInViewport = true;
                    } else {
                        scope.isInViewport = false;
                    }
                });
  
		    }, { 
			root: document,
		  });
  
		  observer.observe(this.block);
		}
	};
	
    AnimGradient.hexToRGB = function(hexval) {
        var hexecuted = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hexval);
        if (!hexecuted) {
            return false;
        }
        return {
            r: parseInt(hexecuted[1], 16),
            g: parseInt(hexecuted[2], 16),
            b: parseInt(hexecuted[3], 16)
        }
    };

    AnimGradient.factor = function(val_1, val_2, lava) { 
        return val_1 * (1 - lava) + val_2 * lava;
    };

    AnimGradient.factorNoise = function(wave_l, x, y, noise) {
        if (this.singleColor) {
            return this.bg_colors[0][wave_l];
        }
        return this.factor(this.bg_colors[0][wave_l], this.bg_colors[1][wave_l], (this.lava(x,y,this.count*this.lavaTime) * this.resAround*3.5)*noise/2 );
        // return this.factor(this.bg_colors[0][channel], this.bg_colors[1][channel], (this.lava(x,y,this.count*this.lavaTime) * this.resAround*2) );
    };

    AnimGradient.lava = function(x, y, grade) {
        var radians = (Math.PI / 180) * grade,
        cos = Math.cos(radians),
        sin = Math.sin(radians),
        nx = (cos * (x - this.cx)) + (sin * (y - this.cy)) + this.cx;
        return nx;
    };
    
    AnimGradient.loopRAF = function() {

      var scope = this;

        if( this.isInViewport ) {
            for (var x = 0; x < this.res; x++) {
                for (var y = 0; y < this.res; y++) {
                    
                    var noise = simplex.noise3D((x / this.res * this.ratioParam.x)*this.size, (y / this.res * this.ratioParam.y)*this.size, this.count/this.opts.time); //original
        
                    this.data[(x + y * this.res) * 4 + 0] = this.factorNoise('r', x, y, noise);
                    this.data[(x + y * this.res) * 4 + 1] = this.factorNoise('g', x, y, noise);
                    this.data[(x + y * this.res) * 4 + 2] = this.factorNoise('b', x, y, noise);
                    this.data[(x + y * this.res) * 4 + 3] = noise*265;
                  
                }
            }
   
            this.count++;
          
            this.ctx.putImageData(this.imgdata, 0, 0);
         
        }

        requestAnimationFrame(function() {
            scope.loopRAF();
        });
    };

    AnimGradient.animLoader = function() {
        this.wrap.classList.add('uncode-canvas-bg-noise-wrap-loaded');
    };

    var anim_init = function() {
        var bgs = _el.querySelectorAll('.block-bg-overlay');
      
        bgs.forEach(function(blockOverlay) {
            var canvasWrap = blockOverlay.querySelector('.uncode-canvas-bg-noise-wrap');
            if( !blockOverlay || !canvasWrap ) {
                return;
            }
            new Uncode_BG_Animated_Gradient(blockOverlay, canvasWrap);
        });
    }

    $(window).on('load', function(){
        anim_init();
    });

    if ( ev === 'shortcode:update' ) {
        anim_init();
    }

};

})(jQuery);
