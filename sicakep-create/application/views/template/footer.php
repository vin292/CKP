<footer class="footer">

        <div class="footer__copyright">

          <div class="container">

            <div class="row">

              <div class="col-md-8">

                <?php

                    if ($this->agent->is_browser())

                    {

                            $agent = $this->agent->browser().' '.$this->agent->version();

                    }

                    elseif ($this->agent->is_robot())

                    {

                            $agent = $this->agent->robot();

                    }

                    elseif ($this->agent->is_mobile())

                    {

                            $agent = $this->agent->mobile();

                    }

                    else

                    {

                            $agent = 'Unidentified User Agent';

                    }



                ?>

                <p class="credit" >Anda menggunakan browser <strong><?php echo $agent ?></strong> pada <strong><?php echo $this->agent->platform() ?></strong></p>

                <p class="credit" >Halaman dimuat dalam <strong>{elapsed_time}</strong> detik. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>

            </div>

              <div class="col-md-4">

                <p class="credit pull-right">&copy;2017 BPS Provinsi Sulawesi Tenggara</p>

                <p class="credit pull-right">Desain Oleh <a href="https://sultradata.com" class="external">Tim IPDS</a></p>

               </div>

            </div>

          </div>

        </div>

      </footer>

    </div>

    <!-- Javascript files-->

    <script src="<?php echo base_url('assets/js/jquery.cookie.js') ?>"></script>

    <script src="<?php echo base_url('assets/js/ekko-lightbox.js') ?>"></script>

    <script src="<?php echo base_url('assets/js/jquery.simple-text-rotator.min.js') ?>"></script>

    <script src="<?php echo base_url('assets/js/jquery.scrollTo.min.js') ?>"></script>

    <script src="<?php echo base_url('assets/js/owl.carousel.min.js') ?>"></script>

    <script src="<?php echo base_url('assets/js/front.js') ?>"></script>

    <script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.validate.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/messages_id.js') ?>"></script>
    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID.-->

    <!---->

    <!-- <script>

      (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=

      function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;

      e=o.createElement(i);r=o.getElementsByTagName(i)[0];

      e.src='//www.google-analytics.com/analytics.js';

      r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));

      ga('create','UA-XXXXX-X');ga('send','pageview');

    </script> -->

  </body>

</html>

