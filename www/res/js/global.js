var GG = {
  G_alert: function(msg, show_style, t){
    if(show_style==null)show_style = 'alert-warning';
    if(t==null)t=3000;
    var info = [{show_style:show_style, msg:msg}];
    $("#global-alert").tmpl(info).appendTo('body');
    setTimeout(function(){
      $('.'+show_style).remove();
    },t);
  },
  placeholder: function(nodes,pcolor) {
    if(nodes.length && !("placeholder" in document_createElement_x("input"))){
      alert(1);
      for(i=0;i<nodes.length;i++){
          var self = nodes[i],
              placeholder = self.getAttribute('placeholder') || '';     
          self.onfocus = function(){
              if(self.value == placeholder){
                 self.value = '';
                 self.style.color = "";
              }               
          }
          self.onblur = function(){
              if(self.value == ''){
                  self.value = placeholder;
                  self.style.color = pcolor;
              }              
          }                                       
          self.value = placeholder;  
          self.style.color = pcolor;              
      }
    }
  }
}
GG.placeholder(document.getElementsByTagName('input'),'#ccc');
$.browser         = {};
$.browser.mozilla = /firefox/.test(navigator.userAgent.toLowerCase());
$.browser.webkit = /webkit/.test(navigator.userAgent.toLowerCase());
$.browser.opera = /opera/.test(navigator.userAgent.toLowerCase());
$.browser.msie = /msie/.test(navigator.userAgent.toLowerCase());
