<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Atturin') }} - Event Coordination Platform</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=lexend:300,400,500,600,700&family=manrope:300,400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="landing">
        <div class="page-shell">
        <header class="lp-nav">
            <div class="container nav-inner">
                <a class="brand" href="{{ url('/') }}">
                    <span class="brand-mark" aria-hidden="true"></span>
                    <span>
                        <span class="brand-name">{{ config('app.name', 'Atturin') }}</span>
                        <span class="brand-tag">Event coordination OS for communities</span>
                    </span>
                </a>

                <nav class="nav-links" aria-label="Navigasi utama">
                    <a href="#solution">Solusi</a>
                    <a href="#workflow">Workflow</a>
                    <a href="#features">Fitur</a>
                    <a href="#use-cases">Use cases</a>
                </nav>

                <div class="nav-cta">
                    @if (Route::has('login'))
                        @auth
                            <a class="btn btn-ghost" href="{{ url('/dashboard') }}">Dashboard</a>
                        @else
                            <a class="btn btn-ghost" href="{{ route('login') }}">Masuk</a>
                            @if (Route::has('register'))
                                <a class="btn btn-primary" href="{{ route('register') }}">Daftar</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </header>

        <main>
            <section class="hero container" id="hero">
                <div class="hero-text fade-up">
                    <span class="badge">
                        <span class="pulse-dot" aria-hidden="true"></span>
                        Built for organizers, not chaos
                    </span>

                    <h1 class="hero-title">
                        Coordinate people, payments, and attendance with
                        <span>{{ config('app.name', 'Atturin') }}</span>.
                    </h1>

                    <p class="hero-lead">
                        Atturin is a modern event coordination platform for community admins. Replace spreadsheets,
                        manual payment chasing, and unclear slots with one clean workflow.
                    </p>

                    <div class="hero-cta">
                        <a class="btn btn-primary" href="#contact">Request demo</a>
                        <a class="btn btn-ghost" href="#workflow">See how it works</a>
                    </div>

                    <div class="hero-proof">
                        <span>
                            <span class="pulse-dot" aria-hidden="true"></span>
                            Payment status tracked in real time
                        </span>
                        <span>Organizer-first workflows</span>
                    </div>
                </div>

                <div class="hero-media" aria-label="Admin previews">
                    <div class="image-card">
                        <div class="image-frame"></div>
                        <div class="image-caption">Organizer dashboard overview</div>
                    </div>
                    <div class="image-card">
                        <div class="image-frame"></div>
                        <div class="image-caption">Payment status + attendance table</div>
                    </div>
                    <div class="image-card">
                        <div class="image-grid">
                            <div class="image-tile"></div>
                            <div class="image-tile"></div>
                            <div class="image-tile"></div>
                        </div>
                        <div class="image-caption">Automated reminders + slot control</div>
                    </div>
                    <div class="mini-cards">
                        <div class="mini-card">
                            <strong>Payments locked</strong>
                            Reminder sent to unpaid members.
                        </div>
                        <div class="mini-card">
                            <strong>Slots full</strong>
                            Waiting list activated automatically.
                        </div>
                    </div>
                </div>
            </section>

            <section class="section section-soft" id="solution">
                <div class="container">
                    <div class="section-head">
                        <div>
                            <h2 class="section-title">Stop chasing people. Start running events.</h2>
                            <p class="section-desc">
                                Atturin centralizes participant management, payment coordination, slot control,
                                and attendance tracking so organizers can focus on the event experience.
                            </p>
                        </div>
                    </div>

                    <div class="split">
                        <div class="card">
                            <span class="pill">Before Atturin</span>
                            <p>Manual chat follow-ups, unclear slots, last-minute cancellations, and payment gaps.</p>
                        </div>
                        <div class="card">
                            <span class="pill">After Atturin</span>
                            <p>Payments are visible, slots stay controlled, and attendance is tracked in one place.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section container" id="workflow">
                <div class="section-head">
                    <div>
                        <h2 class="section-title">A clean workflow for every community event</h2>
                        <p class="section-desc">Three steps that reduce organizer chaos and financial risk.</p>
                    </div>
                </div>

                <div class="workflow">
                    <div class="workflow-step">
                        <span>Step 01</span>
                        <h4>Create the event</h4>
                        <p>Set date, capacity, pricing, and payment rules in minutes.</p>
                    </div>
                    <div class="workflow-step">
                        <span>Step 02</span>
                        <h4>Invite participants</h4>
                        <p>Share a join link and watch slots fill with live status.</p>
                    </div>
                    <div class="workflow-step">
                        <span>Step 03</span>
                        <h4>Track payments & attendance</h4>
                        <p>Know who paid, who confirmed, and who checked in without manual work.</p>
                    </div>
                </div>
            </section>

            <section class="section section-soft" id="features">
                <div class="container">
                    <div class="section-head">
                        <div>
                            <h2 class="section-title">Organizer-first features</h2>
                            <p class="section-desc">Built for admins who manage real budgets and real people.</p>
                        </div>
                    </div>

                    <div class="grid-3">
                        <div class="card">
                            <h3>Payment coordination</h3>
                            <p>Track paid, unpaid, and pending participants automatically with reminders.</p>
                        </div>
                        <div class="card">
                            <h3>Slot management</h3>
                            <p>Cap capacity, lock late joins, and prevent overbooking.</p>
                        </div>
                        <div class="card">
                            <h3>Attendance tracking</h3>
                            <p>Confirm check-ins and attendance history for every event.</p>
                        </div>
                        <div class="card">
                            <h3>Organizer dashboards</h3>
                            <p>See participant activity, payments, and event status in one view.</p>
                        </div>
                        <div class="card">
                            <h3>Automated reminders</h3>
                            <p>Reduce no-shows with scheduled payment and attendance nudges.</p>
                        </div>
                        <div class="card">
                            <h3>Community-ready</h3>
                            <p>Flexible setup for sports, meetups, touring, and ticketed activities.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section container" id="use-cases">
                <div class="section-head">
                    <div>
                        <h2 class="section-title">Beyond sports, built for any community</h2>
                        <p class="section-desc">Start with sports events and scale to meetups, touring, and gatherings.</p>
                    </div>
                </div>

                <div class="grid-3">
                    <div class="card">
                        <h3>Mini tournaments</h3>
                        <p>Handle team slots, shared payments, and attendance in one workflow.</p>
                    </div>
                    <div class="card">
                        <h3>Community meetups</h3>
                        <p>Coordinate headcounts, payment collection, and reminders.</p>
                    </div>
                    <div class="card">
                        <h3>Touring & sunmori</h3>
                        <p>Keep slot assignments, payment status, and confirmations aligned.</p>
                    </div>
                </div>
            </section>

            <section class="section section-soft">
                <div class="container">
                    <div class="section-head">
                        <div>
                            <h2 class="section-title">Numbers that build trust</h2>
                            <p class="section-desc">Metrics organizers care about, always visible.</p>
                        </div>
                    </div>

                    <div class="stat-grid">
                        <div class="stat">
                            <strong>90%</strong>
                            <span>payment clarity after first event</span>
                        </div>
                        <div class="stat">
                            <strong>3x</strong>
                            <span>faster participant confirmation</span>
                        </div>
                        <div class="stat">
                            <strong>1</strong>
                            <span>dashboard for organizers</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="container" id="contact">
                <div class="cta">
                    <div>
                        <h3>Ready to run events without chaos?</h3>
                        <p>Get early access and see how Atturin simplifies payment and attendance operations.</p>
                    </div>
                    <div class="hero-cta">
                        <a class="btn btn-primary" href="mailto:hello@atturin.id">Request demo</a>
                        <a class="btn btn-ghost" href="tel:+628120000000">Talk to us</a>
                    </div>
                </div>
            </section>
        </main>

        <footer class="footer">
            <div class="container footer-grid">
                <div>
                    <h5>{{ config('app.name', 'Atturin') }}</h5>
                    <p>Modern community coordination platform for organizers.</p>
                </div>
                <div>
                    <h5>Menu</h5>
                    <p><a href="#solution">Solution</a></p>
                    <p><a href="#workflow">Workflow</a></p>
                    <p><a href="#features">Features</a></p>
                </div>
                <div>
                    <h5>Kontak</h5>
                    <p>Jakarta, Indonesia</p>
                    <p><a href="mailto:hello@atturin.id">hello@atturin.id</a></p>
                    <p>+62 812 0000 0000</p>
                </div>
            </div>
            <div class="container" style="margin-top: 24px;">
                <p>Copyright {{ date('Y') }} {{ config('app.name', 'Atturin') }}. All rights reserved.</p>
            </div>
        </footer>
        </div>
    </body>
</html>
