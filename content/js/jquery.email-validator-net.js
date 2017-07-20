(function ( $ ) {
  var baseUrl = "https://api.email-validator.net/api/verify"

  $.validateEmail = function(apiKey){
    var apiKey = 'ev-42f1cd54afea3976d23c11a12ee6e30f'
    $.fn.validateEmail = function(cb) {
      var email = this.val();
      $.get(baseUrl + "?EmailAddress=" + email + "&APIKey=" + apiKey, $.proxy(function (res) {
        if (/2[0-9]{2}/g.test(res.status)) {
          this.css('border-color', 'green');
          res['simpleStatus'] = "VALID"
        } else if (/3[0-9]{2}/g.test(res.status)) {
          this.css('border-color', 'orange');
          res['simpleStatus'] = "SUSPECT"
        } else if (/4[0-9]{2}/g.test(res.status)) {
          this.css('border-color', 'red');
          res['simpleStatus'] = "INVALID"
        } else if (/1[0-9]{2}/g.test(res.status)) {
          this.css('border-color', 'black');
          res['simpleStatus'] = "INDETERMINATE"
        }
        cb(res)
      }, this));
      return this;
    };
  }
});
