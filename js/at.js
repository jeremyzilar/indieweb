 (function($){
  jQuery(document).ready(function() {
    $(function(){
      var emojis = [
        "bomb", "smile", "iphone", "girl", "smiley", "heart", "kiss", "copyright", "coffee","a", "ab", "airplane", "alien", "ambulance", "angel", "anger", "angry", "arrow_forward", "arrow_left", "arrow_lower_left", "arrow_lower_right", "arrow_right", "arrow_up", "arrow_upper_left", "arrow_upper_right", "art", "astonished", "atm", "b", "baby", "baby_chick", "baby_symbol", "balloon", "bamboo", "bank", "barber", "baseball", "basketball", "bath", "bear", "beer", "beers", "beginner", "bell", "bento", "bike", "bikini", "bird", "birthday", "black_square", "blue_car", "blue_heart", "blush", "boar", "boat", "bomb", "book", "boot", "bouquet", "bow", "bowtie", "boy", "bread", "briefcase", "broken_heart", "bug", "bulb", "person_with_blond_hair", "phone", "pig", "pill", "pisces", "plus1", "point_down", "point_left", "point_right", "point_up", "point_up_2", "police_car", "poop", "post_office", "postbox", "pray", "princess", "punch", "purple_heart", "question", "rabbit", "racehorse", "radio", "up", "us", "v", "vhs", "vibration_mode", "virgo", "vs", "walking", "warning", "watermelon", "wave", "wc", "wedding", "whale", "wheelchair", "white_square", "wind_chime", "wink", "wink2", "wolf", "woman", "womans_hat", "womens", "x", "yellow_heart", "zap", "zzz", "+1", "-1"
      ]
      var emoji = [
        {key: "rocket", name: "Rocket", c: 'U+1F680'},
        {key: "airplane", name: "Airplane", c: 'U+2708'}

      ]
      var names = ["Jacob","Isabella","Ethan","Emma","Michael","Olivia","Alexander","Sophia","William","Ava","Joshua","Emily","Daniel","Madison","Jayden","Abigail","Noah","Chloe","你好","你你你"];

      var friends = [
        { name: "Jeremy", twitter: '@jeremyzilar', email: 'jeremyzilar@gmail.com'},
        { name: "Dada", twitter: '@jeremyzilar', email: 'jeremyzilar@gmail.com'},
        { name: "Juliette", twitter: '@oubliette', email: 'juliettecezzar@gmail.com'},
        { name: "Mama", twitter: '@oubliette', email: 'juliettecezzar@gmail.com'}
      ];

      var names = $.map(names,function(value,i) {
        return {'id':i,'name':value,'email':value+"@email.com"};
      });
      var emojis = $.map(emojis, function(value, i) {return {key: value, name:value}});

      var at_config = {
        at: "@",
        alias: 'hello',
        data: friends,
        tpl: "<li data-value='@${name}'>${name} <small>${name}</small></li>",
        show_the_at: true
      }
      var emoji_config = {
        at: ":",
        limit: 10,
        data: emoji,
        tpl:"<li data-value=':${key}:'>${name} <img src='http://a248.e.akamai.net/assets.github.com/images/icons/emoji/${key}.png' height='20' width='20' /></li>",
        insert_tpl: "<img src='http://a248.e.akamai.net/assets.github.com/images/icons/emoji/${key}.png' class='emoji' draggable='false' alt='${c}' title='Rocket' aria-label='Emoji: ${name}' data-value=':${key}:'/>",
        delay: 200,
        display_timeout: 300
      }


      // $inputor = $('#inputor').atwho(at_config).atwho(emoji_config);
      // $inputor.caret('pos', 47);
      // $inputor.focus().atwho('run');
      $('#wedit').atwho(at_config).atwho(emoji_config);

      ifr = $('#iframe1')[0]
      ifrBody = ifr.contentDocument.body
      ifrBody.contentEditable = true
      ifrBody.id = 'ifrBody'
      ifrBody.innerHTML = 'For <strong>WYSIWYG</strong> which using <strong>iframe</strong> such as <strong>ckeditor</strong>'
      $(ifrBody).atwho('setIframe', ifr).atwho(at_config)
    });
  });
})(jQuery);