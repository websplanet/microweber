<?php

/*

type: layout

name: Home layout

description: Home site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>


<script>

window.onerror = function(err){alert(err)}


ContactQuery = "<?php print TEMPLATE_URL  ?>contact_submit.php";

$(document).ready(function(){


    $("#contact_form").submit(function(){
     var form = this;
     if(isValid.form(form)){
       var data = getData(form);
       $.post(ContactQuery, data, function(data){

       mw.box.alert('Your message has been sent.');
            $("#contact_form")[0].reset();



       });
     }
   return false;
});


});


</script>


<div class="body_part_inner">
      <div class="contatct_left">
        <form method="post" action="#" id="contact_form">
        <div class="contact_exemp_logo"><img src="<? print TEMPLATE_URL ?>images/contactexemp_logo.jpg" alt="logo" width="365" height="114" /></div>
        <div class="mail_icon"></div>
        <div class="contact_writeus">Write us an e-mail</div>

        <span><input type="text" class="contact_textbox required" name="Name" value="Your Name" /></span>
        <span><input type="text" class="contact_textbox required-email" name="Email" value="Your E-mail" /> </span>
        <span><input type="text" class="contact_textbox required" name="Phone" value="Your Phone" /></span>
        <div class="contact_drop_close"></div>
        <span><textarea name="" cols="" rows="" name="Message" class="contact_message required">Message</textarea></span>
        <div class="contact_send_but">
          <input type="image" src="<? print TEMPLATE_URL ?>images/contact_send_but.jpg" />
        </div>
        </form>
      </div>
      <div class="contact_right">
        <div class="contact_moreinfo"> More information </div>
        <div class="address_phones">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><div class="contacts_address"> <span class="contact_blue">WHERE TO FIND US</span> <br />
                  <br />
                  USA, Dallas<br />
                  Fort Worth metroplex</div></td>
              <td><div class="contacts_phones" ><span class="contact_blue">PHONES</span><br />
                  <br />
                  877.454.6667<br />
                  <br />
                  <span class="contact_blue">FAX</span><br />
                  <br />
                  877.454.6667 </div></td>
            </tr>
          </table>
        </div>
        <div class="contact_email"> <span class="contact_blue">EMAIL</span><br />
          <br />
          <a href="mailto:Info@jobs.exemplarhealthresources.com">Info (at) jobs.exemplarhealthresources.com</a><br />
          <span class="contact_blue"><br />
          FIND US ON THE MAP</span> </div> <?php /*
        <div class="contact_clickhere_but"><img src="<? print TEMPLATE_URL ?>images/contact_clickhere_but.jpg" alt="find us on the map" /></div>    */ ?>

        <iframe width="365" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=USA,+Dallas+Fort+Worth+metroplex&amp;sll=37.0625,-95.677068&amp;sspn=59.119059,135.263672&amp;ie=UTF8&amp;hq=&amp;hnear=Dallas-Fort+Worth+Metroplex&amp;ll=32.707875,-96.920914&amp;spn=0.996843,2.113495&amp;t=m&amp;z=10&amp;iwloc=A&amp;output=embed"></iframe><br />

      <div class="c" style="padding-bottom: 20px;">&nbsp;</div>


      </div>
    </div>

<? include   TEMPLATE_DIR.  "footer.php"; ?>