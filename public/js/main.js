/* main.js
   THEME & DARK-MODE:
   - Robust CSS variables for light/dark. JS sets data-theme on <html> and persists to localStorage (cb_theme).
   - Called ASAP in each HTML via a small inline script; here we control toggle and UI updates.

   DUMMY DATA:
   - 3 courses, 3 users, 3 bookings (seeded once).

   RENDERING & UI:
   - Courses, course detail, booking, profile.
   - Admin: dashboard stats, courses/users/bookings listings, settings.

   REALTIME:
   - Dashboard counters tick; Booking seat availability updates.

   PAYMENT SIMULATION:
   - Card masking, validation, success modal with celebratory burst.

   TODO:
   - Replace localStorage with real APIs (see TODO markers in code).
*/
(
  function () {
    /* ============== Seed Data - DISABLED (Using Laravel Database) ============== */
    const seedData = () => {
      // All data now comes from Laravel Database
      // No localStorage seeding needed
    };

    /* ============== Utilities ============== */
    function datePlusDays(days) { const d = new Date(); d.setDate(d.getDate() + days); return d.toISOString().slice(0, 10); }
    function byId(id) { return document.getElementById(id); }
    function getJSON(key, fallback = []) { try { const v = JSON.parse(localStorage.getItem(key)); return v ?? fallback; } catch { return fallback; } }
    function setJSON(key, value) { localStorage.setItem(key, JSON.stringify(value)); }
    function getQueryParam(name) { return new URLSearchParams(location.search).get(name); }
    function formatUSD(n) { return new Intl.NumberFormat("en-US", { style: "currency", currency: "USD" }).format(n || 0); }
    function setYear() { document.querySelectorAll("#year").forEach(el => el.textContent = new Date().getFullYear()); }

    /* ============== Theme ============== */
    function applyTheme(theme) {
      console.log("[Theme] Applying theme:", theme);
      if (theme === "dark") {
        document.documentElement.classList.add("dark");
        document.documentElement.setAttribute('data-theme', 'dark');
      } else {
        document.documentElement.classList.remove("dark");
        document.documentElement.setAttribute('data-theme', 'light');
      }
      console.log("[Theme] HTML classList:", document.documentElement.classList.toString());

      document.querySelectorAll("#themeToggle").forEach(btn => {
        const icon = btn.querySelector("i");
        if (icon) {
          icon.className = theme === "dark" ? "bi bi-sun text-lg text-amber-400" : "bi bi-moon-stars text-lg";
        }
      });
    }

    function initThemeToggle() {
      console.log("[Theme] Initializing toggle listener on body");
      document.body.addEventListener("click", e => {
        const toggle = e.target.closest("#themeToggle");
        if (!toggle) return;
        console.log("[Theme] Toggle button clicked");
        const isDark = document.documentElement.classList.contains("dark");
        const next = isDark ? "light" : "dark";
        applyTheme(next);
        localStorage.setItem("cb_theme", next);
      });
    }

    /* ============== Toasts & Confirm Dialog ============== */
    function showToast(message, variant = "primary") {
      const container = byId("toastContainer");
      if (!container) return;
      const el = document.createElement("div");
      el.className = `toast align-items-center border-0 show mb-2 text-bg-${variant}`;
      el.setAttribute("role", "alert");
      el.setAttribute("aria-live", "assertive");
      el.setAttribute("aria-atomic", "true");
      el.innerHTML = `<div class="d-flex">
      <div class="toast-body">${message}</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>`;
      container.appendChild(el);
      if (window.bootstrap) {
        setTimeout(() => {
          const toast = bootstrap.Toast.getOrCreateInstance(el, { delay: 2400 });
          toast.show();
          setTimeout(() => el.remove(), 3000);
        }, 10);
      } else {
        // Fallback for no bootstrap
        setTimeout(() => el.remove(), 3000);
      }
    }
    function confirmAction(message = "Are you sure?") {
      return new Promise(resolve => {
        const modalEl = byId("confirmModal");
        if (!modalEl) return resolve(confirm(message));
        byId("confirmMessage").textContent = message;
        const okBtn = byId("confirmOkBtn");
        if (!window.bootstrap) return resolve(confirm(message));
        const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
        const cleanup = () => {
          okBtn.removeEventListener("click", onOk);
          modalEl.removeEventListener("hidden.bs.modal", onCancel);
        };
        const onOk = () => { cleanup(); bsModal.hide(); resolve(true); };
        const onCancel = () => { cleanup(); resolve(false); };
        okBtn.addEventListener("click", onOk, { once: true });
        modalEl.addEventListener("hidden.bs.modal", onCancel, { once: true });
        bsModal.show();
      });
    }

    /* ============== Auth Simulation ============== */
    function setCurrentUser(email) { localStorage.setItem("cb_current_user", email || ""); }
    function getCurrentUser() {
      const email = localStorage.getItem("cb_current_user") || "";
      if (!email) return null;
      return getJSON("cb_users", []).find(u => u.email === email) || null;
    }

    /* ============== Courses & Detail Rendering ============== */
    function stars(rating = 5) {
      const full = Math.floor(rating);
      const half = rating - full >= .5;
      return Array.from({ length: 5 }).map((_, i) => {
        if (i < full) return '<i class="bi bi-star-fill text-warning"></i>';
        if (i === full && half) return '<i class="bi bi-star-half text-warning"></i>';
        return '<i class="bi bi-star text-warning"></i>';
      }).join("");
    }
    // function courseCardHTML(c) {
    //   return `
    //     <div class="col-md-4">
    //       <div class="card card-lift h-100 course-card fade-up" aria-label="${c.title}">
    //         <img src="${c.image}" class="card-img-top" alt="${c.title}">
    //         <div class="card-body d-flex flex-column">
    //           <div class="d-flex justify-content-between align-items-start mb-1">
    //             <h3 class="h5 mb-0 text-truncate">${c.title}</h3>
    //             <span class="badge bg-secondary badge-level">${c.level}</span>
    //           </div>
    //           <div class="small text-muted mb-1">by ${c.instructor}</div>
    //           <div class="d-flex align-items-center gap-2 small mb-2" aria-label="Rating">${stars(c.rating)} <span class="text-muted">(${c.rating.toFixed(1)})</span></div>
    //           <p class="text-truncate-2 mb-3">${c.description}</p>
    //           <div class="d-flex gap-2 mb-3">
    //             <span class="badge duration-badge"><i class="bi bi-clock-history me-1"></i>${c.duration}</span>
    //             <span class="badge price-badge"><i class="bi bi-tag me-1"></i>${formatUSD(c.price)}</span>
    //           </div>
    //           <div class="mt-auto d-flex justify-content-between align-items-center course-actions">
    //             <a href="course-detail.html?id=${encodeURIComponent(c.id)}" class="btn btn-sm btn-outline-secondary btn-lift btn-pill"><i class="bi bi-eye me-1"></i>Quick View</a>
    //             <button class="btn btn-sm btn-primary btn-lift btn-pill" data-book-course="${c.id}" data-bs-toggle="modal" data-bs-target="#bookingModal"><i class="bi bi-cart-plus me-1"></i>Book</button>
    //           </div>
    //         </div>
    //       </div>
    //     </div>`;
    // }
    function renderSkeletonCards(container, count = 3) {
      container.innerHTML = Array.from({ length: count }).map(() => `<div class="col-md-4"><div class="skeleton"></div></div>`).join("");
    }
    function renderFeaturedCourses() {
      // Disabled: Courses are now rendered by Laravel Blade on home page
      return;
      // const container = byId("featuredCourses"); if (!container) return;
      // renderSkeletonCards(container, 3);
      // setTimeout(() => {
      //   const courses = getJSON("cb_courses", []).slice(0, 3);
      //   container.innerHTML = courses.map(courseCardHTML).join("");
      // }, 300);
    }
    function renderCoursesList(filters = {}) {
      const list = byId("coursesList"); if (!list) return;
      renderSkeletonCards(list, 6);
      setTimeout(() => {
        const courses = getJSON("cb_courses", []);
        const filtered = courses.filter(c => {
          const q = (filters.q || "").toLowerCase();
          const level = filters.level || "";
          const date = filters.date || "";
          const matchQ = !q || c.title.toLowerCase().includes(q) || c.instructor.toLowerCase().includes(q);
          const matchLevel = !level || c.level === level;
          const matchDate = !date || c.startDate >= date;
          return matchQ && matchLevel && matchDate;
        });
        list.innerHTML = filtered.map(courseCardHTML).join("");
      }, 250);
    }
    function renderCourseDetail() {
      const container = byId("courseDetailContainer"); if (!container) return;
      const id = getQueryParam("id");
      const course = getJSON("cb_courses", []).find(c => c.id === id);
      if (!course) { container.innerHTML = `<div class="alert alert-warning">Course not found.</div>`; return; }
      container.innerHTML = `
      <div class="row g-4 fade-up">
        <div class="col-lg-6">
          <img src="${course.image}" class="img-fluid rounded-4 shadow-hero" alt="${course.title}">
        </div>
        <div class="col-lg-6">
          <h1 class="h3">${course.title}</h1>
          <div class="mb-2 d-flex align-items-center gap-2">
            <span class="badge bg-secondary badge-level">${course.level}</span>
            <span class="text-muted small">by ${course.instructor}</span>
          </div>
          <div class="mb-2">${stars(course.rating)} <span class="text-muted small">(${course.rating.toFixed(1)})</span></div>
          <p>${course.description}</p>
          <dl class="row">
            <dt class="col-sm-4">Start date</dt><dd class="col-sm-8">${course.startDate}</dd>
            <dt class="col-sm-4">Duration</dt><dd class="col-sm-8">${course.duration}</dd>
            <dt class="col-sm-4">Price</dt><dd class="col-sm-8">${formatUSD(course.price)}</dd>
          </dl>
          <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary btn-lift btn-pill" href="booking.html?course=${encodeURIComponent(course.id)}"><i class="bi bi-calendar2-plus me-1"></i>Book on page</a>
            <button class="btn btn-primary btn-lift btn-pill" data-bs-toggle="modal" data-bs-target="#detailBookingModal" id="openDetailBooking"><i class="bi bi-cart-check me-1"></i>Book now</button>
          </div>
        </div>
      </div>`;
      byId("dbCourse") && (byId("dbCourse").value = course.title);
      byId("openDetailBooking")?.addEventListener("click", () => { const f = byId("dbCourse"); if (f) f.value = course.title; });
    }

    /* ============== Booking Page (Payment) ============== */
    function populateBookingForm() {
      const select = byId("bCourse"); if (!select) return;
      const courses = getJSON("cb_courses", []);
      const preselect = getQueryParam("course");
      select.innerHTML = `<option value="">Select a course</option>${courses.map(c => `<option value="${c.id}">${c.title}</option>`).join("")}`;
      if (preselect) select.value = preselect;
      ["bCourse", "bSeats", "bDate"].forEach(id => byId(id)?.addEventListener("change", () => { updateBookingSummary(); updatePriceBadge(); }));
      updateBookingSummary(); updatePriceBadge();
    }
    function updateBookingSummary() {
      const s = byId("bookingSummary"); if (!s) return;
      const courseId = byId("bCourse")?.value || "";
      const seats = parseInt(byId("bSeats")?.value || "1", 10);
      const date = byId("bDate")?.value || "";
      const course = getJSON("cb_courses", []).find(c => c.id === courseId);
      const total = course ? course.price * (isNaN(seats) ? 1 : seats) : 0;
      s.innerHTML = `
      <dt class="col-4">Course</dt><dd class="col-8">${course ? course.title : "-"}</dd>
      <dt class="col-4">Date</dt><dd class="col-8">${date || "-"}</dd>
      <dt class="col-4">Seats</dt><dd class="col-8">${isNaN(seats) ? 1 : seats}</dd>
      <dt class="col-4">Total</dt><dd class="col-8 fw-semibold">${formatUSD(total)}</dd>`;
    }
    function updatePriceBadge() {
      const badge = byId("priceSummaryBadge"); if (!badge) return;
      const courseId = byId("bCourse")?.value || "";
      const seats = parseInt(byId("bSeats")?.value || "1", 10);
      const course = getJSON("cb_courses", []).find(c => c.id === courseId);
      badge.textContent = formatUSD(course ? course.price * (isNaN(seats) ? 1 : seats) : 0);
    }
    function formatCardNumber(value) {
      return value.replace(/\D/g, "").slice(0, 19).replace(/(.{4})/g, "$1 ").trim();
    }
    function handleBookingPageForm() {
      const form = byId("bookingForm"); if (!form) return;
      const cardFields = byId("cardFields");
      const ccNumber = byId("ccNumber");
      const ccExp = byId("ccExp");
      const ccCvc = byId("ccCvc");
      const prevNum = byId("cardPreviewNumber");
      const prevExp = byId("cardPreviewExp");

      document.body.addEventListener("change", (e) => {
        if (e.target && e.target.name === "paymentMethod") {
          cardFields.style.display = e.target.value === "card" ? "block" : "none";
        }
      });
      ccNumber?.addEventListener("input", () => {
        ccNumber.value = formatCardNumber(ccNumber.value);
        if (prevNum) prevNum.textContent = ccNumber.value || "•••• •••• •••• ••••";
      });
      ccExp?.addEventListener("input", () => {
        ccExp.value = ccExp.value.replace(/[^0-9/]/g, "").slice(0, 5);
        if (prevExp) prevExp.textContent = ccExp.value || "MM/YY";
      });

      form.addEventListener("submit", async e => {
        e.preventDefault();
        if (!form.checkValidity()) return;

        const payMethod = document.querySelector('input[name="paymentMethod"]:checked')?.value || "card";
        if (payMethod === "card") {
          const number = (ccNumber?.value || "").replace(/\s+/g, "");
          const exp = (ccExp?.value || "").trim();
          const cvc = (ccCvc?.value || "").trim();
          const numberOk = /^\d{13,19}$/.test(number);
          const expOk = /^(0[1-9]|1[0-2])\/\d{2}$/.test(exp);
          const cvcOk = /^\d{3,4}$/.test(cvc);
          if (!numberOk || !expOk || !cvcOk) {
            ["ccNumber", "ccExp", "ccCvc"].forEach(id => {
              const el = byId(id);
              const bad = id === "ccNumber" ? !numberOk : id === "ccExp" ? !expOk : !cvcOk;
              if (bad) el.classList.add("is-invalid");
            });
            showToast("Please check your card details", "danger");
            return;
          }
        }

        // Save booking
        const courseId = byId("bCourse").value;
        const bookings = getJSON("cb_bookings", []);
        bookings.push({
          id: `b${Date.now()}`,
          courseId,
          userEmail: byId("bEmail").value.trim(),
          date: byId("bDate").value,
          seats: parseInt(byId("bSeats").value || "1", 10),
          status: "pending"
        });
        setJSON("cb_bookings", bookings);
        showToast("Processing payment...", "info");

        // Simulate payment success
        setTimeout(() => {
          const modal = bootstrap.Modal.getOrCreateInstance(byId("paymentSuccessModal"));
          modal.show();
        }, 850);

        // TODO: POST to /api/bookings
      });
    }

    /* ============== Profile ============== */
    function renderProfile() {
      const form = byId("profileForm"); if (!form) return;
      const user = getCurrentUser() || getJSON("cb_users", [])[0] || { name: "", email: "", bio: "" };
      byId("pName").value = user.name || "";
      byId("pEmail").value = user.email || "";
      byId("pBio").value = user.bio || "";
      const tbody = byId("myBookingsBody");
      const bookings = getJSON("cb_bookings", []).filter(b => b.userEmail === user.email);
      const courses = getJSON("cb_courses", []);
      if (tbody) {
        tbody.innerHTML = bookings.map(b => {
          const c = courses.find(x => x.id === b.courseId);
          return `<tr><td>${c ? c.title : b.courseId}</td><td>${b.date}</td><td>${b.seats}</td><td><span class="badge ${badgeForStatus(b.status)}">${b.status}</span></td></tr>`;
        }).join("");
      }
      const note = byId("noBookingsNote"); if (note) note.textContent = bookings.length ? "" : "You have no bookings yet.";
    }
    function badgeForStatus(status) { if (status === "confirmed") return "bg-success"; if (status === "cancelled") return "bg-danger"; return "bg-secondary"; }

    /* ============== Admin Lists & Stats ============== */
    function renderAdminStatsAndRecent() {
      if (!document.body.dataset.page?.startsWith("admin-")) return;
      const courses = getJSON("cb_courses", []);
      const users = getJSON("cb_users", []);
      const bookings = getJSON("cb_bookings", []);
      if (byId("statCourses")) byId("statCourses").textContent = String(courses.length);
      if (byId("statUsers")) byId("statUsers").textContent = String(users.length);
      if (byId("statBookings")) byId("statBookings").textContent = String(bookings.length);

      const recentBody = byId("recentBookingsBody");
      if (recentBody) {
        const coursesById = Object.fromEntries(courses.map(c => [c.id, c]));
        const items = bookings.slice(-5).reverse();
        recentBody.innerHTML = items.map(b => `
        <tr>
          <td>${coursesById[b.courseId]?.title || b.courseId}</td>
          <td>${b.userEmail}</td>
          <td>${b.date}</td>
          <td>${b.seats}</td>
          <td><span class="badge ${badgeForStatus(b.status)}">${b.status}</span></td>
        </tr>`).join("");
      }
    }
    function renderAdminCourses() {
      const body = byId("adminCoursesBody"); if (!body) return;
      const courses = getJSON("cb_courses", []);
      body.innerHTML = courses.map(c => `
      <tr>
        <td>${c.title}<div class="small text-muted d-md-none">by ${c.instructor}</div></td>
        <td class="d-none d-md-table-cell">${c.instructor}</td>
        <td><span class="badge bg-secondary">${c.level}</span></td>
        <td>${formatUSD(c.price)}</td>
        <td class="text-end">
          <a class="btn btn-sm btn-outline-secondary btn-lift btn-pill" href="course-edit.html?id=${encodeURIComponent(c.id)}"><i class="bi bi-pencil-square me-1"></i>Edit page</a>
          <button class="btn btn-sm btn-primary btn-lift btn-pill" data-edit-course="${c.id}" data-bs-toggle="modal" data-bs-target="#courseModal"><i class="bi bi-sliders me-1"></i>Edit modal</button>
          <button class="btn btn-sm btn-outline-danger btn-lift btn-pill" data-delete-course="${c.id}"><i class="bi bi-trash3 me-1"></i>Delete</button>
        </td>
      </tr>`).join("");
    }
    function renderAdminUsers() {
      const body = byId("adminUsersBody"); if (!body) return;
      const users = getJSON("cb_users", []);
      body.innerHTML = users.map(u => `
      <tr>
        <td>${u.name}</td>
        <td class="d-none d-md-table-cell">${u.email}</td>
        <td><span class="badge ${u.role === "admin" ? "bg-primary" : "bg-secondary"}">${u.role}</span></td>
        <td class="text-end">
          <button class="btn btn-sm btn-primary btn-lift btn-pill" data-edit-user="${u.id}" data-bs-toggle="modal" data-bs-target="#userModal"><i class="bi bi-pencil-square me-1"></i>Edit</button>
          <button class="btn btn-sm btn-outline-danger btn-lift btn-pill" data-delete-user="${u.id}"><i class="bi bi-trash3 me-1"></i>Delete</button>
        </td>
      </tr>`).join("");
    }
    function renderAdminBookings() {
      const body = byId("adminBookingsBody"); if (!body) return;
      const bookings = getJSON("cb_bookings", []);
      const coursesById = Object.fromEntries(getJSON("cb_courses", []).map(c => [c.id, c]));
      body.innerHTML = bookings.map(b => `
      <tr>
        <td>${coursesById[b.courseId]?.title || b.courseId}</td>
        <td>${b.userEmail}</td>
        <td>${b.date}</td>
        <td>${b.seats}</td>
        <td><span class="badge ${badgeForStatus(b.status)}">${b.status}</span></td>
        <td class="text-end">
          <button class="btn btn-sm btn-primary btn-lift btn-pill" data-booking-status="${b.id}" data-bs-toggle="modal" data-bs-target="#bookingStatusModal"><i class="bi bi-pencil-square me-1"></i>Update</button>
          <button class="btn btn-sm btn-outline-danger btn-lift btn-pill" data-delete-booking="${b.id}"><i class="bi bi-trash3 me-1"></i>Delete</button>
        </td>
      </tr>`).join("");
    }

    /* ============== Validation ============== */
    function enableBootstrapValidation() {
      document.querySelectorAll(".needs-validation").forEach(form => {
        form.addEventListener("submit", event => {
          if (!form.checkValidity()) { event.preventDefault(); event.stopPropagation(); }
          form.classList.add("was-validated");
        });
      });
    }

    /* ============== Auth Forms ============== */
    function handleLogin() {
      const form = byId("loginForm"); if (!form) return;
      form.addEventListener("submit", e => {
        e.preventDefault();
        if (!form.checkValidity()) return;
        const email = byId("lEmail").value.trim();
        const users = getJSON("cb_users", []);
        const exists = users.find(u => u.email === email);
        if (exists) {
          setCurrentUser(email);
          showToast("Signed in successfully", "success");
          setTimeout(() => location.href = "../profile.html", 500);
        } else {
          showToast("No account found. Please register.", "warning");
          setTimeout(() => location.href = "register.html", 700);
        }
      });
    }
    function handleRegister() {
      const form = byId("registerForm"); if (!form) return;
      form.addEventListener("submit", e => {
        e.preventDefault();
        if (!form.checkValidity()) return;
        const name = byId("rName").value.trim();
        const email = byId("rEmail").value.trim();
        const pass = byId("rPassword").value;
        const conf = byId("rConfirm").value;
        if (pass !== conf) { byId("rConfirm").classList.add("is-invalid"); return; }
        const users = getJSON("cb_users", []);
        if (users.some(u => u.email === email)) { showToast("Email already registered.", "danger"); return; }
        users.push({ id: `u${Date.now()}`, name, email, role: "student", bio: "" });
        setJSON("cb_users", users);
        setCurrentUser(email);
        showToast("Account created", "success");
        setTimeout(() => location.href = "../profile.html", 600);
      });
    }
    function handleForgot() {
      const form = byId("forgotForm"); if (!form) return;
      form.addEventListener("submit", e => {
        e.preventDefault();
        if (!form.checkValidity()) return;
        showToast("If the email exists, a reset link has been sent.", "info");
        setTimeout(() => location.href = "login.html", 800);
      });
    }

    /* ============== Quick Booking (Courses & Detail) ============== */
    function handleQuickBookingButtons() {
      document.body.addEventListener("click", e => {
        const btn = e.target.closest("[data-book-course]"); if (!btn) return;
        const id = btn.getAttribute("data-book-course");
        const course = getJSON("cb_courses", []).find(c => c.id === id);
        const qbCourse = byId("qbCourse");
        if (qbCourse && course) qbCourse.value = course.title;
      });
    }
    function handleQuickBookingForm() {
      const form = byId("quickBookingForm"); if (!form) return;
      form.addEventListener("submit", e => {
        e.preventDefault();
        if (!form.checkValidity()) return;
        const title = byId("qbCourse").value;
        const course = getJSON("cb_courses", []).find(c => c.title === title);
        const bookings = getJSON("cb_bookings", []);
        bookings.push({ id: `b${Date.now()}`, courseId: course?.id || "", userEmail: byId("qbEmail").value.trim(), date: byId("qbDate").value, seats: 1, status: "pending" });
        setJSON("cb_bookings", bookings);
        bootstrap.Modal.getInstance(byId("bookingModal"))?.hide();
        showToast("Booking submitted", "success");
      });
    }
    function handleDetailBookingForm() {
      const form = byId("detailBookingForm"); if (!form) return;
      form.addEventListener("submit", e => {
        e.preventDefault();
        if (!form.checkValidity()) return;
        const title = byId("dbCourse").value;
        const course = getJSON("cb_courses", []).find(c => c.title === title);
        const bookings = getJSON("cb_bookings", []);
        bookings.push({ id: `b${Date.now()}`, courseId: course?.id || "", userEmail: byId("dbEmail").value.trim(), date: byId("dbDate").value, seats: 1, status: "pending" });
        setJSON("cb_bookings", bookings);
        bootstrap.Modal.getInstance(byId("detailBookingModal"))?.hide();
        showToast("Booking submitted", "success");
      });
    }

    /* ============== Admin Modals ============== */
    function handleAdminCourseModal() {
      const form = byId("courseForm"); const modalEl = byId("courseModal");
      if (!form || !modalEl) return;

      modalEl.addEventListener("show.bs.modal", e => {
        const button = e.relatedTarget;
        const editId = button?.getAttribute("data-edit-course");
        byId("courseModalLabel").innerHTML = `<i class="bi bi-${editId ? "pencil-square" : "plus-circle"} me-1"></i>${editId ? "Edit course" : "Add course"}`;
        form.reset(); form.classList.remove("was-validated");
        if (editId) {
          const c = getJSON("cb_courses", []).find(x => x.id === editId);
          if (c) {
            byId("cId").value = c.id;
            byId("cTitle").value = c.title;
            byId("cInstructor").value = c.instructor;
            byId("cLevel").value = c.level;
            byId("cPrice").value = c.price;
            byId("cStart").value = c.startDate;
            byId("cDesc").value = c.description;
          }
        } else byId("cId").value = "";
      });

      form.addEventListener("submit", async e => {
        e.preventDefault();
        if (!form.checkValidity()) return;

        const courses = getJSON("cb_courses", []);
        const id = byId("cId").value || `c${Date.now()}`;
        const obj = {
          id,
          title: byId("cTitle").value.trim(),
          instructor: byId("cInstructor").value.trim(),
          level: byId("cLevel").value,
          price: parseFloat(byId("cPrice").value),
          startDate: byId("cStart").value,
          description: byId("cDesc").value.trim(),
          image: "https://images.unsplash.com/photo-1513258496099-48168024aec0?q=80&w=1200&auto=format&fit=crop",
          rating: 4.5,
          duration: "10h"
        };

        if (!(await confirmAction(byId("cId").value ? "Save changes to this course?" : "Create new course?"))) return;

        const idx = courses.findIndex(c => c.id === id);
        if (idx >= 0) courses[idx] = obj; else courses.push(obj);
        setJSON("cb_courses", courses);
        bootstrap.Modal.getInstance(byId("courseModal"))?.hide();
        renderAdminCourses();
        showToast("Course saved", "success");
      });

      document.body.addEventListener("click", async e => {
        const del = e.target.closest("[data-delete-course]"); if (!del) return;
        if (!(await confirmAction("Delete this course? This action cannot be undone."))) return;
        const id = del.getAttribute("data-delete-course");
        const courses = getJSON("cb_courses", []).filter(c => c.id !== id);
        setJSON("cb_courses", courses);
        renderAdminCourses();
        showToast("Course deleted", "danger");
      });
    }

    function handleAdminUsersModal() {
      const form = byId("userForm"); const modalEl = byId("userModal");
      if (!form || !modalEl) return;

      modalEl.addEventListener("show.bs.modal", e => {
        const button = e.relatedTarget;
        const editId = button?.getAttribute("data-edit-user");
        byId("userModalLabel").innerHTML = `<i class="bi bi-${editId ? "pencil-square" : "person-plus"} me-1"></i>${editId ? "Edit user" : "Add user"}`;
        form.reset(); form.classList.remove("was-validated");
        if (editId) {
          const u = getJSON("cb_users", []).find(x => x.id === editId);
          if (u) {
            byId("uId").value = u.id;
            byId("uName").value = u.name;
            byId("uEmail").value = u.email;
            byId("uRole").value = u.role;
          }
        } else byId("uId").value = "";
      });

      form.addEventListener("submit", async e => {
        e.preventDefault();
        if (!form.checkValidity()) return;

        const users = getJSON("cb_users", []);
        const id = byId("uId").value || `u${Date.now()}`;
        const obj = {
          id,
          name: byId("uName").value.trim(),
          email: byId("uEmail").value.trim(),
          role: byId("uRole").value,
          bio: ""
        };

        if (!(await confirmAction(byId("uId").value ? "Save user changes?" : "Create new user?"))) return;

        const idx = users.findIndex(u => u.id === id);
        if (idx >= 0) users[idx] = obj; else users.push(obj);
        setJSON("cb_users", users);
        bootstrap.Modal.getInstance(byId("userModal"))?.hide();
        renderAdminUsers();
        showToast("User saved", "success");
      });

      document.body.addEventListener("click", async e => {
        const del = e.target.closest("[data-delete-user]"); if (!del) return;
        if (!(await confirmAction("Delete this user?"))) return;
        const id = del.getAttribute("data-delete-user");
        const users = getJSON("cb_users", []).filter(u => u.id !== id);
        setJSON("cb_users", users);
        renderAdminUsers();
        showToast("User deleted", "danger");
      });
    }

    function handleAdminBookingsModal() {
      const form = byId("bookingStatusForm"); const modalEl = byId("bookingStatusModal");
      if (!form || !modalEl) return;

      modalEl.addEventListener("show.bs.modal", e => {
        const id = e.relatedTarget?.getAttribute("data-booking-status");
        byId("bsId").value = id || "";
        const b = getJSON("cb_bookings", []).find(x => x.id === id);
        if (b) byId("bsStatus").value = b.status;
      });

      form.addEventListener("submit", async e => {
        e.preventDefault();
        if (!form.checkValidity()) return;
        const id = byId("bsId").value;
        const bookings = getJSON("cb_bookings", []);
        const idx = bookings.findIndex(x => x.id === id);
        if (idx >= 0) {
          if (!(await confirmAction("Save booking status?"))) return;
          bookings[idx].status = byId("bsStatus").value;
          setJSON("cb_bookings", bookings);
          bootstrap.Modal.getInstance(byId("bookingStatusModal"))?.hide();
          renderAdminBookings();
          showToast("Booking updated", "success");
        }
      });

      document.body.addEventListener("click", async e => {
        const del = e.target.closest("[data-delete-booking]"); if (!del) return;
        if (!(await confirmAction("Delete this booking?"))) return;
        const id = del.getAttribute("data-delete-booking");
        const bookings = getJSON("cb_bookings", []).filter(b => b.id !== id);
        setJSON("cb_bookings", bookings);
        renderAdminBookings();
        showToast("Booking deleted", "danger");
      });
    }

    /* ============== Realtime Simulations ============== */
    let seatTimer = null;
    function simulateSeatAvailability() {
      const label = byId("bSeatsAvailable"); if (!label) return;
      const value = Math.max(0, Math.floor(10 + Math.random() * 20) - Math.floor(Math.random() * 8));
      label.textContent = value.toString();
    }
    function startSeatTicker() { simulateSeatAvailability(); clearInterval(seatTimer); seatTimer = setInterval(simulateSeatAvailability, 4000); }

    let dashboardTimer = null;
    function pulseStat(el) { if (!el) return; el.classList.add("pulse"); setTimeout(() => el.classList.remove("pulse"), 400); }
    function startDashboardRealtime() {
      const active = byId("statActiveUsers");
      const today = byId("statBookingsToday");
      const conv = byId("statConversion");
      const revenue = byId("statRevenue");
      const bookings = getJSON("cb_bookings", []);
      const baseRevenue = bookings.reduce((sum, b) => {
        const c = getJSON("cb_courses", []).find(x => x.id === b.courseId);
        return sum + (c ? c.price * b.seats : 0);
      }, 0);
      function tick() {
        const a = 20 + Math.floor(Math.random() * 40);
        const t = 1 + Math.floor(Math.random() * 12);
        const cv = (3 + Math.random() * 4).toFixed(1);
        const rv = baseRevenue + Math.floor(Math.random() * 500);
        if (active) { active.textContent = String(a); pulseStat(active); }
        if (today) { today.textContent = String(t); pulseStat(today); }
        if (conv) { conv.textContent = `${cv}%`; pulseStat(conv); }
        if (revenue) { revenue.textContent = formatUSD(rv); pulseStat(revenue); }
      }
      tick();
      clearInterval(dashboardTimer);
      dashboardTimer = setInterval(tick, 3500);
    }

    /* ============== Admin Sidebar (collapse state) ============== */
    function initSidebar() {
      const collapsed = localStorage.getItem("cb_sidebar_collapsed") === "1";
      if (collapsed) document.body.classList.add("collapsed");
      document.getElementById("sidebarToggle")?.addEventListener("click", () => {
        document.body.classList.toggle("collapsed");
        localStorage.setItem("cb_sidebar_collapsed", document.body.classList.contains("collapsed") ? "1" : "0");
      });
    }

    /* ============== Auth & Profile ============== */
    function handleLogin() {
      const form = byId("loginForm"); if (!form) return;
      form.addEventListener("submit", e => {
        e.preventDefault();
        if (!form.checkValidity()) return;
        const email = byId("lEmail").value.trim();
        const users = getJSON("cb_users", []);
        const exists = users.find(u => u.email === email);
        if (exists) {
          setCurrentUser(email);
          showToast("Signed in successfully", "success");
          setTimeout(() => location.href = "../profile.html", 500);
        } else {
          showToast("No account found. Please register.", "warning");
          setTimeout(() => location.href = "register.html", 700);
        }
      });
    }
    function handleRegister() {
      const form = byId("registerForm"); if (!form) return;
      form.addEventListener("submit", e => {
        e.preventDefault();
        if (!form.checkValidity()) return;
        const name = byId("rName").value.trim();
        const email = byId("rEmail").value.trim();
        const pass = byId("rPassword").value;
        const conf = byId("rConfirm").value;
        if (pass !== conf) { byId("rConfirm").classList.add("is-invalid"); return; }
        const users = getJSON("cb_users", []);
        if (users.some(u => u.email === email)) { showToast("Email already registered.", "danger"); return; }
        users.push({ id: `u${Date.now()}`, name, email, role: "student", bio: "" });
        setJSON("cb_users", users);
        setCurrentUser(email);
        showToast("Account created", "success");
        setTimeout(() => location.href = "../profile.html", 600);
      });
    }
    function handleForgot() {
      const form = byId("forgotForm"); if (!form) return;
      form.addEventListener("submit", e => {
        e.preventDefault();
        if (!form.checkValidity()) return;
        showToast("If the email exists, a reset link has been sent.", "info");
        setTimeout(() => location.href = "login.html", 800);
      });
    }
    function handleProfileSave() {
      const form = byId("profileForm"); if (!form) return;
      form.addEventListener("submit", e => {
        e.preventDefault();
        if (!form.checkValidity()) return;
        const users = getJSON("cb_users", []);
        const email = byId("pEmail").value.trim();
        const current = getCurrentUser() || users[0];
        if (!current) return;
        current.name = byId("pName").value.trim();
        current.email = email;
        current.bio = byId("pBio").value;
        const idx = users.findIndex(u => u.id === current.id);
        if (idx !== -1) users[idx] = current;
        setJSON("cb_users", users);
        setCurrentUser(email);
        showToast("Profile saved", "success");
      });
    }

    /* ============== Init ============== */
    function init() {
      // seedData(); // Disabled - using Laravel Database
      setYear();
      // applyTheme(localStorage.getItem("cb_theme") || "light");
      // initThemeToggle();
      enableBootstrapValidation();
      initSidebar();

      const page = document.body.dataset.page || "";

      if (page === "home") renderFeaturedCourses();

      if (page === "courses") {
        renderCoursesList();
        handleQuickBookingButtons();
        handleQuickBookingForm();
        byId("courseFilterForm")?.addEventListener("submit", e => {
          e.preventDefault();
          renderCoursesList({
            q: byId("searchQuery").value,
            level: byId("levelFilter").value,
            date: byId("dateFilter").value
          });
        });
      }

      if (page === "course-detail") {
        renderCourseDetail();
        handleDetailBookingForm();
      }

      if (page === "booking") {
        populateBookingForm();
        handleBookingPageForm();
        startSeatTicker();
        document.body.addEventListener("change", (e) => { if (e.target?.id === "bCourse") simulateSeatAvailability(); });
      }

      if (page === "profile") {
        renderProfile();
        // handleProfileSave();
      }

      if (page === "login") /* handleLogin(); */;
      if (page === "register") /* handleRegister(); */;
      if (page === "forgot-password") /* handleForgot(); */;

      if (page === "admin-dashboard") {
        renderAdminStatsAndRecent();
        startDashboardRealtime();
      }
      if (page === "admin-courses") {
        renderAdminCourses();
        handleAdminCourseModal();
      }
      if (page === "admin-users") {
        renderAdminUsers();
        handleAdminUsersModal();
      }
      if (page === "admin-bookings") {
        renderAdminBookings();
        handleAdminBookingsModal();
      }
      if (page === "admin-course-edit") {
        // Reuse render courses data for consistency (pre-filled in form)
        const id = getQueryParam("id");
        const course = getJSON("cb_courses", []).find(c => c.id === id);
        if (!course) { alert("Course not found."); location.href = "courses.html"; return; }
        byId("ecId").value = course.id;
        byId("ecTitle").value = course.title;
        byId("ecInstructor").value = course.instructor;
        byId("ecLevel").value = course.level;
        byId("ecPrice").value = course.price;
        byId("ecStart").value = course.startDate;
        byId("ecDesc").value = course.description;

        byId("editCourseForm").addEventListener("submit", async e => {
          e.preventDefault();
          if (!e.target.checkValidity()) return;
          const courses = getJSON("cb_courses", []);
          const idx = courses.findIndex(c => c.id === id);
          if (!(await confirmAction("Save course changes?"))) return;
          courses[idx] = {
            ...courses[idx],
            title: byId("ecTitle").value.trim(),
            instructor: byId("ecInstructor").value.trim(),
            level: byId("ecLevel").value,
            price: parseFloat(byId("ecPrice").value),
            startDate: byId("ecStart").value,
            description: byId("ecDesc").value.trim()
          };
          setJSON("cb_courses", courses);
          showToast("Course saved", "success");
          setTimeout(() => location.href = "courses.html", 600);
        });
      }
      if (page === "admin-settings") {
        renderAdminStatsAndRecent();
        byId("brandingForm")?.addEventListener("submit", e => {
          e.preventDefault();
          if (!e.target.checkValidity()) return;
          const settings = getJSON("cb_settings", {});
          settings.name = byId("sName").value.trim();
          settings.primary = byId("sPrimary").value;
          setJSON("cb_settings", settings);
          showToast("Branding saved", "success");
          // TODO: PUT to /api/settings/branding
        });
        byId("emailForm")?.addEventListener("submit", e => {
          e.preventDefault();
          if (!e.target.checkValidity()) return;
          const settings = getJSON("cb_settings", {});
          settings.from = byId("sFrom").value.trim();
          settings.notify = byId("sNotify").value;
          setJSON("cb_settings", settings);
          showToast("Email settings saved", "success");
          // TODO: PUT to /api/settings/email
        });
      }
    }

    document.addEventListener("DOMContentLoaded", init);


    document.querySelectorAll(".payment-radio").forEach(radio => {
      radio.addEventListener("change", function () {

        const paypalForm = document.getElementById("paypalForm");

        if (this.value === "paypal") {
          paypalForm.classList.remove("d-none");
        } else {
          paypalForm.classList.add("d-none");
        }
      });
    });



    // Form validation
    (function () {
      'use strict'
      const form = document.getElementById('bookingForm')
      if (!form) return;

      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        } else {
          // Show loading state
          const btn = document.getElementById('submitBtn');
          const btnText = document.getElementById('btnText');
          const btnIcon = document.getElementById('btnIcon');
          const btnSpinner = document.getElementById('btnSpinner');

          if (btn && btnText && btnIcon && btnSpinner) {
            btn.disabled = true;
            btnSpinner.classList.remove('d-none');
            btnIcon.classList.add('d-none');
            btnText.textContent = "booking processing";
          }
        }
        form.classList.add('was-validated')
      }, false)
    })()

    // Update payment method display
    document.querySelectorAll('input[name="payment_gateway"]').forEach(radio => {
      radio.addEventListener('change', function () {
        const badge = document.getElementById('paymentMethodBadge');
        const hiddenInput = document.getElementById('selectedPaymentMethod');

        if (this.value === 'paymob') {
          if (badge) {
            badge.textContent = 'Paymob';
            badge.className = 'badge bg-primary-subtle text-primary';
          }
          if (hiddenInput) hiddenInput.value = 'online';
        } else {
          if (badge) {
            badge.textContent = "booking cash";
            badge.className = 'badge bg-secondary-subtle text-secondary';
          }
          if (hiddenInput) hiddenInput.value = 'cash';
        }
      });
    });


  })();
