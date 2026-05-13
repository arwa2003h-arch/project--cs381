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

        if (
          cardText.includes(searchValue) &&
          (selectedMajor === 'all' || cardMajor === selectedMajor)
        ) {
          card.style.display = 'flex';
        } else {
          card.style.display = 'none';
        }
      });
    }

    searchInput.addEventListener('input', filterCourses);
    majorFilter.addEventListener('change', filterCourses);
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

});