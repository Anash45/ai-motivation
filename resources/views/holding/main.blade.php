<div class="main-wrapper demo-06">
    <!-- hero-area start  -->
    <div class="hero-area">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <!-- heading start  -->
                    <div class="heading">
                        <h1 class="text-white wow fadeInUp" data-wow-delay=".2s">Launching Soon</h1>
                        <h2 class="text-white wow fadeInUp mb-4" data-wow-delay=".2s">Lift Your Vibe From The Moment
                            You
                            Rise!</h2>
                    </div>
                    <!-- heading end  -->
                </div>
                <div class="col-lg-6">
                    <!-- countdown start  -->
                    <p class="wow fadeInRight text-white" style="font-size: 26px;" data-wow-delay="0">The ultimate premium wellness
                        initiator,
                        delivering your 7am daily motivational MP3 audio message, named uniquely to you.</p>
                    <!-- countdown end  -->
                </div>
                <div class="col-lg-6">
                    <form id="subscribe-form" class="row g-2">
                        <div class="col pr-0 wow fadeInLeft" data-wow-delay=".2s">
                            <input type="email" name="email" id="email"
                                class="form-control rounded-right-0 bg-dark border-dark text-white"
                                placeholder="Enter your email" required>
                        </div>
                        <div class="col-auto pl-0 wow fadeInRight" data-wow-delay=".4s">
                            <button type="submit" class="btn btn-warning rounded-left-0">
                                <i class="lni lni-envelope"></i>
                            </button>
                        </div>
                    </form>
                    <div id="subscribe-message" class="mt-3"></div>
                    <!-- desc strat  -->
                    <p class="wow fadeInRight" data-wow-delay=".4s">Submit your email address below for advance
                        notification of launch
                        and 7-day free trial subscription.</p>
                    <!-- desc end  -->
                </div>
            </div>
        </div>
    </div>
    <!-- hero-area end  -->
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("subscribe-form").addEventListener("submit", function (e) {
            e.preventDefault();
            const email = document.getElementById("email").value;
            const messageDiv = document.getElementById("subscribe-message");

            // Show "Sending..." message
            messageDiv.innerHTML = `
                        <div id="subscribe-alert" class="alert alert-info mt-3">
                            Sending...
                        </div>
                    `;

            fetch("/subscribe", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({ email })
            })
                .then(res => res.json())
                .then(data => {
                    let alertType = data.status === "success" ? "alert-success" : "alert-danger";
                    messageDiv.innerHTML = `
                                <div id="subscribe-alert" class="alert ${alertType} mt-3">
                                    ${data.message}
                                </div>
                            `;

                    if (data.status === "success") {
                        setTimeout(() => {
                            const alert = document.getElementById("subscribe-alert");
                            if (alert) {
                                alert.remove();
                            }
                        }, 5000);
                    }
                })
                .catch(() => {
                    messageDiv.innerHTML = `
                                <div class="alert alert-danger mt-3">
                                    Something went wrong. Try again.
                                </div>
                            `;
                });
        });
    });
</script>