!function(n,t){"use strict";function e(n,e,o,c){a.directive(n,["$parse","swipe",function(a,i){var u=75,r=.3,s=30;return function(h,f,l){function v(n){if(!p||!d)return!1;var t=(n.y-p.y)*e,a=(n.x-p.x)*e;return o?Math.abs(a)<u&&t>0&&t>s&&Math.abs(a)/t<r:Math.abs(t)<u&&a>0&&a>s&&Math.abs(t)/a<r}var p,d,w=a(l[n]),g=["touch"];t.isDefined(l.ngSwipeDisableMouse)||g.push("mouse"),i.bind(f,{start:function(n,t){var e=t.target.getAttribute("class");o&&(!e||e&&null===e.match("noPreventDefault"))&&t.preventDefault(),p=n,d=!0},cancel:function(){d=!1},end:function(n,t){v(n)&&h.$apply(function(){f.triggerHandler(c),w(h,{$event:t})})}},g)}}])}var a=t.module("swipe",[]);a.factory("swipe",[function(){function n(n){var t=n.originalEvent||n,e=t.touches&&t.touches.length?t.touches:[t],a=t.changedTouches&&t.changedTouches[0]||e[0];return{x:a.clientX,y:a.clientY}}function e(n,e){var a=[];return t.forEach(n,function(n){var t=c[n][e];t&&a.push(t)}),a.join(" ")}var a=40,o=.3,c={mouse:{start:"mousedown",move:"mousemove",end:"mouseup"},touch:{start:"touchstart",move:"touchmove",end:"touchend",cancel:"touchcancel"}};return{bind:function(t,c,i){var u,r,s,h,f=!1,l=!1,v=!0;i=i||["mouse","touch"],t.on(e(i,"start"),function(t){s=n(t),f=!0,u=0,r=0,l=!1,v=!0,h=s,c.start&&c.start(s,t)}),t.on(e(i,"cancel"),function(n){f=!1,c.cancel&&c.cancel(n)}),t.on(e(i,"move"),function(t){if(f&&s){var e=n(t);if(u+=Math.abs(e.x-h.x),r+=Math.abs(e.y-h.y),h=e,!(a>u&&a>r)){if(!l){var i,p,d;i=Math.abs(e.x-s.x),p=Math.abs(e.y-s.y),d=p/i,o>d?(t.preventDefault(),v=!1):v=!0,l=!0}t.isVertical=v,c.move&&c.move(e,t)}}}),t.on(e(i,"end"),function(t){f&&(t.isVertical=v,f=!1,c.end&&c.end(n(t),t))})}}}]);try{t.module("ngTouch")}catch(o){e("ngSwipeLeft",-1,!1,"swipeleft"),e("ngSwipeRight",1,!1,"swiperight")}e("ngSwipeUp",-1,!0,"swipeup"),e("ngSwipeDown",1,!0,"swipedown")}(window,window.angular);