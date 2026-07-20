@extends('layouts.app')

@section('title', 'Contact — Ain Sheba')

@section('content')

<section class="ain-section">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="fw-bold">Contact Us</h1>
            <p class="lead text-muted">Have a question? We'd love to hear from you.</p>
        </div>

        <div class="row g-5">
            {{-- Contact Info --}}
            <div class="col-md-4">
                <h4 class="fw-semibold mb-4">Get in Touch</h4>
                <ul class="list-unstyled">
                    <li class="mb-3 d-flex align-items-start gap-3">
                        <i class="bi bi-geo-alt-fill fs-5 text-warning mt-1"></i>
                        <div>
                            <div class="fw-semibold">Address</div>
                            <div class="text-muted">Dhaka, Bangladesh</div>
                        </div>
                    </li>
                    <li class="mb-3 d-flex align-items-start gap-3">
                        <i class="bi bi-envelope-fill fs-5 text-warning mt-1"></i>
                        <div>
                            <div class="fw-semibold">Email</div>
                            <div class="text-muted">support@ainsheba.bd</div>
                        </div>
                    </li>
                    <li class="mb-3 d-flex align-items-start gap-3">
                        <i class="bi bi-telephone-fill fs-5 text-warning mt-1"></i>
                        <div>
                            <div class="fw-semibold">Phone</div>
                            <div class="text-muted">+880 1700-000000</div>
                        </div>
                    </li>
                    <li class="d-flex align-items-start gap-3">
                        <i class="bi bi-clock-fill fs-5 text-warning mt-1"></i>
                        <div>
                            <div class="fw-semibold">Office Hours</div>
                            <div class="text-muted">Sun–Thu: 9 AM – 5 PM</div>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Contact Form --}}
            <div class="col-md-8">
                <div class="card border-0 shadow-sm p-4">
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label for="contact_name" class="form-label fw-semibold">Your Name</label>
                                <input type="text" id="contact_name" name="name" class="form-control" placeholder="Rahim Uddin" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="contact_email" class="form-label fw-semibold">Email Address</label>
                                <input type="email" id="contact_email" name="email" class="form-control" placeholder="rahim@example.com" required>
                            </div>
                            <div class="col-12">
                                <label for="contact_subject" class="form-label fw-semibold">Subject</label>
                                <input type="text" id="contact_subject" name="subject" class="form-control" placeholder="How can we help?" required>
                            </div>
                            <div class="col-12">
                                <label for="contact_message" class="form-label fw-semibold">Message</label>
                                <textarea id="contact_message" name="message" class="form-control" rows="5" placeholder="Write your message here..." required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn ain-btn-accent px-5"><i class="bi bi-send"></i>Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
