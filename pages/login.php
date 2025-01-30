<!-- Section -->
<main>
    <section class="vh-lg-100 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center form-bg-image" data-background-lg="assets/img/illustrations/signin.svg">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <div class="signin-inner my-3 my-lg-0 bg-white shadow-soft border rounded border-light p-4 p-lg-5 w-100 fmxw-500">
                        <div class="text-center text-md-center mb-4 mt-md-0">
                            <img src="assets/img/<?= $_SESSION['logo'] ?>" width="50px">
                            <p><b>Surat Keterangan Pendamping Ijazah (SKPI)</b><br><?= $_SESSION['nama_pt'] ?></p>
                        </div>
                        <form id="form" class="mt-4">
                            <!-- Form -->
                            <div class="form-group mb-4">
                                <label for="email">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><span class="fas fa-user"></span></span>
                                    <input type="text" class="form-control" placeholder="Username" name="username" id="username" autofocus>
                                </div>  
                            </div>
                            <!-- End of Form -->
                            <div class="form-group">
                                <!-- Form -->
                                <div class="form-group mb-4">
                                    <label for="password">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon2"><span class="fas fa-unlock-alt"></span></span>
                                        <input type="password" placeholder="Password" class="form-control" name="password" id="password">
                                    </div>  
                                </div>
                                <!-- End of Form -->
                                <!-- <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck5">
                                        <label class="form-check-label" for="defaultCheck5">
                                            Remember me
                                        </label>
                                    </div>
                                    <div><a href="#" class="small text-right">Lost password?</a></div>
                                </div> -->
                            </div>
                            <button name="submit" type="submit" class="btn btn-block btn-primary">Sign in</button>
                        </form>
                        <!-- <div class="mt-3 mb-4 text-center">
                            <span class="font-weight-normal">or login with</span>
                        </div> -->
                        <div class="btn-wrapper my-4 text-center">
                            <!-- <button class="btn btn-icon-only btn-pill btn-outline-light text-facebook mr-2" type="button" aria-label="facebook button" title="facebook button">
                                <span aria-hidden="true" class="fab fa-facebook-f"></span>
                            </button>
                            <button class="btn btn-icon-only btn-pill btn-outline-light text-twitter mr-2" type="button" aria-label="twitter button" title="twitter button">
                                <span aria-hidden="true" class="fab fa-twitter"></span>
                            </button>
                            <button class="btn btn-icon-only btn-pill btn-outline-light text-facebook" type="button" aria-label="github button" title="github button">
                                <span aria-hidden="true" class="fab fa-github"></span>
                            </button> -->
                        </div>
                        <div class="d-flex justify-content-center align-items-center mt-4">
                            <span class="font-weight-normal">
                                <a href="logout.html" class="font-weight-bold">Clear Cache Browser</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script src="assets/js/jquery-1.9.1.js"></script>
<script>
    $(function () {
        $('form').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: 'pages/proses_login.php',
                data: $('form').serialize(),
                success: function (a) {
                  if(a==""){
                    location.reload();
                }
                else{
                    Swal.fire("Warning !", a, 'error');
                    $("#username").focus();
                }
            }
        });
        });

    });
</script>