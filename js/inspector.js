function createXPathFromElement(elm) { 
    var allNodes = document.getElementsByTagName('*'); 
    for (segs = []; elm && elm.nodeType == 1; elm = elm.parentNode) 
    { 
        if (elm.hasAttribute('id')) {
			
            if (elm.id=='asdqwe1234_picker') 
                return null;
			
            var uniqueIdCount = 0; 
            for (var n=0;n < allNodes.length;n++) { 
                if (allNodes[n].hasAttribute('id') && allNodes[n].id == elm.id) uniqueIdCount++; 
                if (uniqueIdCount > 1) break; 
            }; 
            if ( uniqueIdCount == 1) { 
                segs.unshift('id("' + elm.getAttribute('id') + '")'); 
                return segs.join('/'); 
            } else { 
                segs.unshift(elm.localName.toLowerCase() + '[@id="' + elm.getAttribute('id') + '"]'); 
            } 
        } else if (elm.hasAttribute('class')) { 
            segs.unshift(elm.localName.toLowerCase() + '[@class="' + elm.getAttribute('class') + '"]'); 
        } else { 
            for (i = 1, sib = elm.previousSibling; sib; sib = sib.previousSibling) { 
                if (sib.localName == elm.localName)  i++;
            }; 
            segs.unshift(elm.localName.toLowerCase() + '[' + i + ']'); 
        }; 
    }; 
    return segs.length ? '/' + segs.join('/') : null; 
}; 

function Overlay(width, height, left, top) {

    this.width = this.height = this.left = this.top = 0;

    // outer parent	
    var outer = document.createElement("div");
    outer.id = "asdqwe1234_picker";
    outer.className = "asdqwe1234_outer";
    
    document.getElementsByTagName("body")[0].appendChild(outer);
	
    // red lines (boxes)        
    var topbox = document.createElement("div");
    topbox.style.height = "1px";
    outer.appendChild(topbox);

    var bottombox = document.createElement("div");
    bottombox.style.height = "1px";
    outer.appendChild(bottombox);
    
    var leftbox = document.createElement("div");
    leftbox.style.width = "1px";
    outer.appendChild(leftbox);

    var rightbox = document.createElement("div");
    rightbox.style.width = "1px";
    outer.appendChild(rightbox);
    
    // don't count it as a real element
    outer.addEventListener("mouseover", function() {
        outer.style.display = "none";
    });
 
    /**
     * Public interface
     */
    this.resize = function resize(width, height, left, top) {
        if (width != null)
            this.width = width;
        if (height != null)
            this.height = height;
        if (left != null)
            this.left = left;
        if (top != null)
            this.top = top;      
    };

    this.show = function show() {
        outer.style.display = "block";
    };

    this.hide = function hide() {
        outer.style.display = "none";
    };     

    this.render = function render(width, height, left, top) {

        this.resize(width, height, left, top);

        topbox.style.top    = this.top+"px";
        topbox.style.left   = this.left+"px";
        topbox.style.width  = this.width+"px";

        bottombox.style.top    = (this.top + this.height - 1)+"px";
        bottombox.style.left   = this.left+"px";
        bottombox.style.width  = this.width+"px";

        leftbox.style.top       = this.top+"px";
        leftbox.style.left      = this.left+"px";
        leftbox.style.height    = this.height+"px";
        
        rightbox.style.top      = this.top+"px";
        rightbox.style.left     = (this.left + this.width - 1)+"px";
        rightbox.style.height   = this.height+"px";
        
        this.show();
    };      
}

/* helpers */
function getOffset( el ) {
    var _x = 0;
    var _y = 0;
    while( el && !isNaN( el.offsetLeft ) && !isNaN( el.offsetTop ) ) {
        _x += el.offsetLeft - el.scrollLeft;
        _y += el.offsetTop - el.scrollTop;
        el = el.offsetParent;
    }
    return { top: parseInt(_y), left: parseInt(_x) };
}


window.addEventListener("load", function() { 
    var box = new Overlay(200, 200, 400, 20);

    document.getElementsByTagName("body")[0].addEventListener('click',function(e) {
        parent.document.getElementById('element').value=createXPathFromElement(e.target);
    });

    document.getElementsByTagName("body")[0].addEventListener('mouseover',function(e) {
        var offset = getOffset(e.target);
        box.render(e.target.offsetWidth, e.target.offsetHeight, offset.left, offset.top);	
    });	
}, false );
