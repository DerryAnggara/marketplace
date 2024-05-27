<footer class="footer">
            <div class="cta cta-horizontal cta-horizontal-box bg-dark bg-image" style="background-image: url('/front/assets/images/demos/demo-14/bg-1.jpg');">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-xl-8 offset-xl-2">
                            <div class="row align-items-center">
                                <div class="col-lg-5 cta-txt">
                                    <h3 class="cta-title text-primary">Join Our Newsletter</h3><!-- End .cta-title -->
                                    <p class="cta-desc text-light">Subcribe to get information about products and coupons</p><!-- End .cta-desc -->
                                </div><!-- End .col-lg-5 -->
                                
                                <div class="col-lg-7">
                                    <form action="#">
                                        <div class="input-group">
                                            <input type="email" class="form-control" placeholder="Enter your Email Address" aria-label="Email Adress" required>
                                            <div class="input-group-append">
                                                <button class="btn" type="submit">Subscribe</button>
                                            </div><!-- .End .input-group-append -->
                                        </div><!-- .End .input-group -->
                                    </form>
                                </div><!-- End .col-lg-7 -->
                            </div><!-- End .row -->
                        </div><!-- End .col-xl-8 offset-2 -->
                    </div><!-- End .row -->
                </div><!-- End .container-fluid -->
            </div><!-- End .cta -->
            <div class="footer-middle border-0">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12 col-lg-4">
                            <div class="widget widget-about">
                                <img src="/images/site/{{ get_settings()->site_logo}}" class="footer-logo" alt="Footer Logo">
                                <p>{{ get_settings()->site_meta_description }} </p>
                                
                                <div class="widget-about-info">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-4">
                                            <span class="widget-about-title">Got Question? Call us 24/7</span>
                                            <a href="tel:0215523429">{{ get_settings()->site_phone}}</a>
                                        </div><!-- End .col-sm-6 -->
                                        <div class="col-sm-6 col-md-4">
                                            <span class="widget-about-title">Email Address:</span>
                                            <a href="mailto:{{get_settings()->site_email}}">{{ get_settings()->site_email}}</a>
                                        </div><!-- End .col-sm-6 -->
                                        <div class="col-sm-6 col-md-8 col-mt-2">
                                            <span class="widget-about-title">Address</span>
                                            <a href="http://maps.google.com/maps?q=<?= urlencode(get_settings()->site_address) ?>" target="_blank">{{ get_settings()->site_address}}</a>
                                        </div><!-- End .col-sm-6 -->
                                        <div class="col-sm-6 col-md-8">
                                            <span class="widget-about-title">Payment Method</span>
                                            <figure class="footer-payments">
                                                <img src="/images/site/{{ get_settings()->site_logo }}" alt="Payment methods" width="272" height="20">
                                            </figure><!-- End .footer-payments -->
                                        </div><!-- End .col-sm-6 -->
                                    </div><!-- End .row -->
                                </div><!-- End .widget-about-info -->
                            </div><!-- End .widget about-widget -->
                        </div><!-- End .col-sm-12 col-lg-4 -->

                        <div class="col-sm-4 col-lg-2">
                            {{-- <div class="widget">
                                <h4 class="widget-title">Useful Links</h4><!-- End .widget-title -->

                                <ul class="widget-list">
                                    <li><a href="about.html">About Molla</a></li>
                                    <li><a href="#">How to shop on Molla</a></li>
                                    <li><a href="faq.html">FAQ</a></li>
                                    <li><a href="contact.html">Contact us</a></li>
                                    <li><a href="login.html">Log in</a></li>
                                </ul><!-- End .widget-list -->
                            </div><!-- End .widget --> --}}
                        </div><!-- End .col-sm-4 col-lg-2 -->

                        <div class="col-sm-4 col-lg-2">
                            {{-- <div class="widget">
                                <h4 class="widget-title">Customer Service</h4><!-- End .widget-title -->

                                <ul class="widget-list">
                                    <li><a href="#">Payment Methods</a></li>
                                    <li><a href="#">Money-back guarantee!</a></li>
                                    <li><a href="#">Returns</a></li>
                                    <li><a href="#">Shipping</a></li>
                                    <li><a href="#">Terms and conditions</a></li>
                                    <li><a href="#">Privacy Policy</a></li>
                                </ul><!-- End .widget-list -->
                            </div><!-- End .widget --> --}}
                        </div><!-- End .col-sm-4 col-lg-2 -->

                        <div class="col-sm-4 col-lg-2">
                            {{-- <div class="widget">
                                <h4 class="widget-title">My Account</h4><!-- End .widget-title -->

                                <ul class="widget-list">
                                    <li><a href="#">Sign In</a></li>
                                    <li><a href="cart.html">View Cart</a></li>
                                    <li><a href="#">My Wishlist</a></li>
                                    <li><a href="#">Track My Order</a></li>
                                    <li><a href="#">Help</a></li>
                                </ul><!-- End .widget-list -->
                            </div><!-- End .widget --> --}}
                        </div><!-- End .col-sm-4 col-lg-2 -->

                        <div class="col-sm-4 col-lg-2">
                            {{-- <div class="widget widget-newsletter">
                                <h4 class="widget-title">Sign Up to Newsletter</h4><!-- End .widget-title -->

                                <p>Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan.</p>
                                
                                <form action="#">
                                    <div class="input-group">
                                        <input type="email" class="form-control" placeholder="Enter your Email Address" aria-label="Email Adress" required>
                                        <div class="input-group-append">
                                            <button class="btn btn-dark" type="submit"><i class="icon-long-arrow-right"></i></button>
                                        </div><!-- .End .input-group-append -->
                                    </div><!-- .End .input-group -->
                                </form>
                            </div><!-- End .widget --> --}}
                        </div><!-- End .col-sm-4 col-lg-2 -->
                    </div><!-- End .row -->
                </div><!-- End .container-fluid -->
            </div><!-- End .footer-middle -->

            <div class="footer-bottom">
                <div class="container-fluid">
                    <p class="footer-copyright">Copyright © 2019 Molla Store. All Rights Reserved.</p><!-- End .footer-copyright -->
                    <div class="social-icons social-icons-color">
                        <span class="social-label">Social Media</span>
                        <a href="{{get_social_network()->facebook_url}}" class="social-icon social-facebook" title="Facebook" target="_blank"><i class="icon-facebook-f"></i></a>
                        <a href="{{get_social_network()->X_url}}" class="social-icon social-twitter" title="Twitter" target="_blank"><i class="icon-twitter"></i></a>
                        <a href="{{get_social_network()->instagram_url}}" class="social-icon social-instagram" title="Instagram" target="_blank"><i class="icon-instagram"></i></a>
                    </div><!-- End .soial-icons -->
                </div><!-- End .container-fluid -->
            </div><!-- End .footer-bottom -->
        </footer><!-- End .footer -->