/*
 * imagesLoaded PACKAGED v3.1.7
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */
(function(){function E(){}function C(I,H){for(var G=I.length;G--;){if(I[G].listener===H){return G}}return -1}function B(G){return function(){return this[G].apply(this,arguments)}}var D=E.prototype,A=this,F=A.EventEmitter;D.getListeners=function(J){var H,G,I=this._getEvents();if("object"==typeof J){H={};for(G in I){I.hasOwnProperty(G)&&J.test(G)&&(H[G]=I[G])}}else{H=I[J]||(I[J]=[])}return H},D.flattenListeners=function(I){var H,G=[];for(H=0;I.length>H;H+=1){G.push(I[H].listener)}return G},D.getListenersAsObject=function(I){var H,G=this.getListeners(I);return G instanceof Array&&(H={},H[I]=G),H||G},D.addListener=function(K,H){var J,G=this.getListenersAsObject(K),I="object"==typeof H;for(J in G){G.hasOwnProperty(J)&&-1===C(G[J],H)&&G[J].push(I?H:{listener:H,once:!1})}return this},D.on=B("addListener"),D.addOnceListener=function(H,G){return this.addListener(H,{listener:G,once:!0})},D.once=B("addOnceListener"),D.defineEvent=function(G){return this.getListeners(G),this},D.defineEvents=function(H){for(var G=0;H.length>G;G+=1){this.defineEvent(H[G])}return this},D.removeListener=function(K,H){var J,G,I=this.getListenersAsObject(K);for(G in I){I.hasOwnProperty(G)&&(J=C(I[G],H),-1!==J&&I[G].splice(J,1))}return this},D.off=B("removeListener"),D.addListeners=function(H,G){return this.manipulateListeners(!1,H,G)},D.removeListeners=function(H,G){return this.manipulateListeners(!0,H,G)},D.manipulateListeners=function(L,I,H){var K,G,M=L?this.removeListener:this.addListener,J=L?this.removeListeners:this.addListeners;if("object"!=typeof I||I instanceof RegExp){for(K=H.length;K--;){M.call(this,I,H[K])}}else{for(K in I){I.hasOwnProperty(K)&&(G=I[K])&&("function"==typeof G?M.call(this,K,G):J.call(this,K,G))}}return this},D.removeEvent=function(J){var H,G=typeof J,I=this._getEvents();if("string"===G){delete I[J]}else{if("object"===G){for(H in I){I.hasOwnProperty(H)&&J.test(H)&&delete I[H]}}else{delete this._events}}return this},D.removeAllListeners=B("removeEvent"),D.emitEvent=function(L,I){var H,K,G,M,J=this.getListenersAsObject(L);for(G in J){if(J.hasOwnProperty(G)){for(K=J[G].length;K--;){H=J[G][K],H.once===!0&&this.removeListener(L,H.listener),M=H.listener.apply(this,I||[]),M===this._getOnceReturnValue()&&this.removeListener(L,H.listener)}}}return this},D.trigger=B("emitEvent"),D.emit=function(H){var G=Array.prototype.slice.call(arguments,1);return this.emitEvent(H,G)},D.setOnceReturnValue=function(G){return this._onceReturnValue=G,this},D._getOnceReturnValue=function(){return this.hasOwnProperty("_onceReturnValue")?this._onceReturnValue:!0},D._getEvents=function(){return this._events||(this._events={})},E.noConflict=function(){return A.EventEmitter=F,E},"function"==typeof define&&define.amd?define("eventEmitter/EventEmitter",[],function(){return E}):"object"==typeof module&&module.exports?module.exports=E:this.EventEmitter=E}).call(this),function(E){function C(H){var G=E.event;return G.target=G.target||G.srcElement||H,G}var B=document.documentElement,D=function(){};B.addEventListener?D=function(I,H,G){I.addEventListener(H,G,!1)}:B.attachEvent&&(D=function(I,G,H){I[G+H]=H.handleEvent?function(){var J=C(I);H.handleEvent.call(H,J)}:function(){var J=C(I);H.call(I,J)},I.attachEvent("on"+G,I[G+H])});var A=function(){};B.removeEventListener?A=function(I,H,G){I.removeEventListener(H,G,!1)}:B.detachEvent&&(A=function(J,H,G){J.detachEvent("on"+H,J[H+G]);try{delete J[H+G]}catch(I){J[H+G]=void 0}});var F={bind:D,unbind:A};"function"==typeof define&&define.amd?define("eventie/eventie",F):E.eventie=F}(this),function(B,A){"function"==typeof define&&define.amd?define(["eventEmitter/EventEmitter","eventie/eventie"],function(C,D){return A(B,C,D)}):"object"==typeof exports?module.exports=A(B,require("eventEmitter"),require("eventie")):B.imagesLoaded=A(B,B.EventEmitter,B.eventie)}(window,function(G,K,B){function A(Q,P){for(var O in P){Q[O]=P[O]}return Q}function I(O){return"[object Array]"===F.call(O)}function C(R){var P=[];if(I(R)){P=R}else{if("number"==typeof R.length){for(var O=0,Q=R.length;Q>O;O++){P.push(R[O])}}else{P.push(R)}}return P}function J(R,Q,P){if(!(this instanceof J)){return new J(R,Q)}"string"==typeof R&&(R=document.querySelectorAll(R)),this.elements=C(R),this.options=A({},this.options),"function"==typeof Q?P=Q:A(this.options,Q),P&&this.on("always",P),this.getImages(),D&&(this.jqDeferred=new D.Deferred);var O=this;setTimeout(function(){O.check()})}function E(O){this.img=O}function H(O){this.src=O,M[O]=this}var D=G.jQuery,L=G.console,N=L!==void 0,F=Object.prototype.toString;J.prototype=new K,J.prototype.options={},J.prototype.getImages=function(){this.images=[];for(var S=0,V=this.elements.length;V>S;S++){var P=this.elements[S];"IMG"===P.nodeName&&this.addImage(P);var O=P.nodeType;if(O&&(1===O||9===O||11===O)){for(var T=P.querySelectorAll("img"),Q=0,U=T.length;U>Q;Q++){var R=T[Q];this.addImage(R)}}}},J.prototype.addImage=function(P){var O=new E(P);this.images.push(O)},J.prototype.check=function(){function S(V,U){return Q.options.debug&&N&&L.log("confirm",V,U),Q.progress(V),P++,P===R&&Q.complete(),!0}var Q=this,P=0,R=this.images.length;if(this.hasAnyBroken=!1,!R){return this.complete(),void 0}for(var O=0;R>O;O++){var T=this.images[O];T.on("confirm",S),T.check()}},J.prototype.progress=function(P){this.hasAnyBroken=this.hasAnyBroken||!P.isLoaded;var O=this;setTimeout(function(){O.emit("progress",O,P),O.jqDeferred&&O.jqDeferred.notify&&O.jqDeferred.notify(O,P)})},J.prototype.complete=function(){var P=this.hasAnyBroken?"fail":"done";this.isComplete=!0;var O=this;setTimeout(function(){if(O.emit(P,O),O.emit("always",O),O.jqDeferred){var Q=O.hasAnyBroken?"reject":"resolve";O.jqDeferred[Q](O)}})},D&&(D.fn.imagesLoaded=function(Q,P){var O=new J(this,Q,P);return O.jqDeferred.promise(D(this))}),E.prototype=new K,E.prototype.check=function(){var P=M[this.img.src]||new H(this.img.src);if(P.isConfirmed){return this.confirm(P.isLoaded,"cached was confirmed"),void 0}if(this.img.complete&&void 0!==this.img.naturalWidth){return this.confirm(0!==this.img.naturalWidth,"naturalWidth"),void 0}var O=this;P.on("confirm",function(R,Q){return O.confirm(R.isLoaded,Q),!0}),P.check()},E.prototype.confirm=function(P,O){this.isLoaded=P,this.emit("confirm",this,O)};var M={};return H.prototype=new K,H.prototype.check=function(){if(!this.isChecked){var O=new Image;B.bind(O,"load",this),B.bind(O,"error",this),O.src=this.src,this.isChecked=!0}},H.prototype.handleEvent=function(P){var O="on"+P.type;this[O]&&this[O](P)},H.prototype.onload=function(O){this.confirm(!0,"onload"),this.unbindProxyEvents(O)},H.prototype.onerror=function(O){this.confirm(!1,"onerror"),this.unbindProxyEvents(O)},H.prototype.confirm=function(P,O){this.isConfirmed=!0,this.isLoaded=P,this.emit("confirm",this,O)},H.prototype.unbindProxyEvents=function(O){B.unbind(O.target,"load",this),B.unbind(O.target,"error",this)},J});