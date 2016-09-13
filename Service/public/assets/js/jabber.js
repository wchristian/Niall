var makeAjaxNiallRequest = function(question){
  jQuery.post(
      "/v1/speak",
      {
          "Message": question
      },
      function(jsonResponse){
          jQuery('form.chat .log').append("<li><span class='author'>Niall:</span> " + jsonResponse.reply + "</li>");
      }
  );
};

jQuery(document).ready(function(){
    jQuery("form.chat").submit(function(e){
        e.preventDefault();
        var questionInput = jQuery("form.chat input#question");
        var question = questionInput.val();
        questionInput.val('').focus();
        jQuery('form.chat .log').append("<li><span class='author'>You:</span> " + question + "</li>");

        makeAjaxNiallRequest(question);
    });
});