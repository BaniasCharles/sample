document.addEventListener('DOMContentLoaded', function() {
    // Initialize active tab
    if (!document.querySelector('.nav-tab.active')) {
      const firstTab = document.querySelector('.nav-tab');
      if (firstTab) {
        firstTab.classList.add('active');
        const tabName = firstTab.getAttribute('data-tab');
        document.getElementById(tabName).classList.add('active');
      }
    }
    
    // Add smooth scroll to all links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
          behavior: 'smooth'
        });
      });
    });
    
    // Initialize tooltips
    const tooltipTriggers = document.querySelectorAll('[data-tooltip]');
    tooltipTriggers.forEach(el => {
      el.addEventListener('mouseenter', showTooltip);
      el.addEventListener('mouseleave', hideTooltip);
    });
  });
  
  // Tab functionality
  function openTab(tabId, element) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(tab => {
      tab.classList.remove('active');
    });
    
    // Deactivate all tab buttons
    document.querySelectorAll('.nav-tab').forEach(button => {
      button.classList.remove('active');
    });
    
    // Activate selected tab
    document.getElementById(tabId).classList.add('active');
    element.classList.add('active');
  }
  
  // Delete confirmation
  function deleteLog(id) {
    if (confirm("Are you sure you want to delete this log? This action cannot be undone.")) {
      window.location.href = `delete_log.php?id=${id}`;
    }
  }
  
  // Show tooltip
  function showTooltip(e) {
    const tooltipText = this.getAttribute('data-tooltip');
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.textContent = tooltipText;
    document.body.appendChild(tooltip);
    
    const rect = this.getBoundingClientRect();
    tooltip.style.left = `${rect.left + rect.width/2 - tooltip.offsetWidth/2}px`;
    tooltip.style.top = `${rect.top - tooltip.offsetHeight - 5}px`;
  }
  
  // Hide tooltip
  function hideTooltip() {
    const tooltip = document.querySelector('.tooltip');
    if (tooltip) {
      tooltip.remove();
    }
  }
  
  // Form validation
  function validateForm(form) {
    let valid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
      if (!field.value.trim()) {
        field.style.borderColor = 'var(--danger)';
        valid = false;
      } else {
        field.style.borderColor = '';
      }
    });
    
    function confirmDeleteReview(reviewId) {
      if (confirm("Are you sure you want to delete this supervisor review?\nThis action cannot be undone.")) {
          window.location.href = `delete_review.php?id=${reviewId}`;
      }
  }
    return valid;
  }