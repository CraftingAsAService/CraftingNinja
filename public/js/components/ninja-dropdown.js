Vue.directive("click-outside",{bind:function(t,n,i){t.clickOutsideEvent=function(o){t==o.target||t.contains(o.target)||i.context[n.expression](o)},document.body.addEventListener("click",t.clickOutsideEvent)},unbind:function(t){document.body.removeEventListener("click",t.clickOutsideEvent)}}),Vue.component("ninja-dropdown",{props:["title","icon","placeholder","option","options"],template:"\n\t\t<div class='post-filter__select ninja-dropdown' v-click-outside='close'>\n\t\t\t<label class='post-filter__label'>\n\t\t\t\t<i :class='icon + \" mr-1\"'></i>\n\t\t\t\t{{ title }}\n\t\t\t</label>\n\t\t\t<div :class='\"cs-select cs-skin-border \" + (open ? \" cs-active\" : \"\")'>\n\t\t\t\t<span class='cs-placeholder' v-on:click='open = ! open'>{{ placeholder }}</span>\n\t\t\t\t<div class='cs-options'>\n\t\t\t\t\t<ul>\n\t\t\t\t\t\t<li v-for='optionData in options' v-on:click='optionClick(option, optionData[\"key\"])'>\n\t\t\t\t\t\t\t<span>\n\t\t\t\t\t\t\t\t<i :class='\"fas \" + optionData[\"icon\"] + \" mr-1\"'></i>\n\t\t\t\t\t\t\t\t{{ optionData[\"title\"] }}\n\t\t\t\t\t\t\t</span>\n\t\t\t\t\t\t</li>\n\t\t\t\t\t</ul>\n\t\t\t\t</div>\n\t\t\t</div>\n\t\t</div>\n\t",data:function(){return{open:!1}},methods:{close:function(){this.open=!1},optionClick:function(t,n){this.$emit("clicked",t,n),this.close()}}});