document.addEventListener('DOMContentLoaded', function () {

  const searchInput = document.querySelector('#searchInput');
  const majorFilter = document.querySelector('#majorFilter');
  const courseCards = document.querySelectorAll('.course-card');

  if (searchInput && majorFilter && courseCards.length > 0) {
    function filterCourses() {
      const searchValue = searchInput.value.toLowerCase().trim();
      const selectedMajor = majorFilter.value.toLowerCase();

      courseCards.forEach(function (card) {
        const cardText = card.textContent.toLowerCase();
        const cardMajor = card.getAttribute('data-major').toLowerCase();

        if (cardText.includes(searchValue) && (selectedMajor === 'all' || cardMajor === selectedMajor)) {
          card.style.display = 'flex';
        } else {
          card.style.display = 'none';
        }
      });
    }

    searchInput.addEventListener('input', filterCourses);
    majorFilter.addEventListener('change', filterCourses);
  }

  const registerForm = document.querySelector('#registerForm');

  if (registerForm) {
    registerForm.addEventListener('submit', function (event) {
      event.preventDefault();

      const fullName = document.querySelector('#fullName').value.trim();
      const username = document.querySelector('#registerUsername').value.trim();
      const password = document.querySelector('#registerPassword').value.trim();
      const confirmPassword = document.querySelector('#confirmPassword').value.trim();
      const message = document.querySelector('#registerMessage');

      message.style.color = '#dc2626';

      if (fullName === '' || username === '' || password === '' || confirmPassword === '') {
        message.textContent = 'Please fill in all fields.';
        return;
      }

      if (password !== confirmPassword) {
        message.textContent = 'Passwords do not match.';
        return;
      }

      localStorage.setItem('registeredStudent', JSON.stringify({
        fullName: fullName,
        username: username,
        password: password,
        role: 'student'
      }));

      message.style.color = 'green';
      message.textContent = 'Registration successful. You can now login.';
      registerForm.reset();
    });
  }

  const loginForm = document.querySelector('#loginForm');

  if (loginForm) {
    loginForm.addEventListener('submit', function (event) {
      event.preventDefault();

      const username = document.querySelector('#loginUsername').value.trim();
      const password = document.querySelector('#loginPassword').value.trim();
      const role = document.querySelector('#loginRole').value;
      const message = document.querySelector('#loginMessage');

      message.style.color = '#dc2626';

      if (username === '' || password === '' || role === '') {
        message.textContent = 'Please fill in all fields.';
        return;
      }

      if (role === 'admin' && username === 'admin' && password === '12345678') {
        localStorage.setItem('currentUser', JSON.stringify({
          username: 'admin',
          role: 'admin'
        }));

        window.location.href = 'admin-dashboard.html';
        return;
      }

      const storedStudentText = localStorage.getItem('registeredStudent');

      if (storedStudentText) {
        const storedStudent = JSON.parse(storedStudentText);

        if (role === 'student' && username === storedStudent.username && password === storedStudent.password) {
          localStorage.setItem('currentUser', JSON.stringify({
            username: storedStudent.username,
            fullName: storedStudent.fullName,
            role: 'student'
          }));

          window.location.href = 'index.html';
          return;
        }
      }

      message.textContent = 'Invalid login information.';
    });
  }

  const feedbackForm = document.querySelector('#feedbackForm');

  if (feedbackForm) {
    feedbackForm.addEventListener('submit', function (event) {
      event.preventDefault();

      const rating = document.querySelector('#rating').value.trim();
      const comment = document.querySelector('#comment').value.trim();
      const message = document.querySelector('#feedbackMessage');
      const currentUser = localStorage.getItem('currentUser');

      message.style.color = '#dc2626';

      if (!currentUser) {
        message.textContent = 'Please login first.';
        return;
      }

      if (rating === '' || comment === '') {
        message.textContent = 'Please provide both rating and comment.';
        return;
      }

      message.style.color = 'green';
      message.textContent = 'Feedback submitted successfully.';
      feedbackForm.reset();
    });
  }

  const dashboardMajor = document.querySelector('#dashboardMajor');
  const dashboardPerformance = document.querySelector('#dashboardPerformance');
  const performanceTable = document.querySelector('#performanceTable');

  if (dashboardMajor && dashboardPerformance && performanceTable) {
    const dashboardRows = performanceTable.querySelectorAll('tbody tr');

    function filterDashboard() {
      const selectedMajor = dashboardMajor.value;
      const selectedPerformance = dashboardPerformance.value;

      dashboardRows.forEach(function (row) {
        const rowMajor = row.getAttribute('data-major');
        const rowPerformance = row.getAttribute('data-performance');

        if (
          (selectedMajor === 'all' || rowMajor === selectedMajor) &&
          (selectedPerformance === 'all' || rowPerformance === selectedPerformance)
        ) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    }

    dashboardMajor.addEventListener('change', filterDashboard);
    dashboardPerformance.addEventListener('change', filterDashboard);
    filterDashboard();
  }

  const logoutBtn = document.querySelector('#logoutBtn');

  if (logoutBtn) {
    logoutBtn.addEventListener('click', function () {
      localStorage.removeItem('currentUser');
    });
  }

});
