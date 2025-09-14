document.addEventListener('DOMContentLoaded', function() {
    // Handle form submission
    const diagnosisForm = document.getElementById('diagnosis-form');
    if (diagnosisForm) {
        diagnosisForm.addEventListener('submit', function(e) {
            // Validate form
            const riskFactors = document.querySelectorAll('input[name="risk_factors[]"]:checked');
            const symptoms = document.querySelectorAll('input[name="symptoms[]"]:checked');
            
            if (riskFactors.length === 0 && symptoms.length === 0) {
                e.preventDefault();
                alert('Silakan pilih minimal satu faktor resiko atau gejala.');
                return false;
            }
            
            return true;
        });
    }
    
    // Toggle all checkboxes in a group
    const toggleAllButtons = document.querySelectorAll('.toggle-all');
    if (toggleAllButtons) {
        toggleAllButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const checkboxGroup = this.getAttribute('data-target');
                const checkboxes = document.querySelectorAll(`input[name="${checkboxGroup}[]"]`);
                const isChecked = this.getAttribute('data-state') === 'checked';
                
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = !isChecked;
                });
                
                this.setAttribute('data-state', isChecked ? 'unchecked' : 'checked');
                this.textContent = isChecked ? 'Pilih Semua' : 'Batal Pilih';
            });
        });
    }
    
    // Toggle none (clear all) checkboxes in a group
    const toggleNoneButtons = document.querySelectorAll('.toggle-none');
    if (toggleNoneButtons) {
        toggleNoneButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const checkboxGroup = this.getAttribute('data-target');
                const checkboxes = document.querySelectorAll(`input[name="${checkboxGroup}[]"]`);
                
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                });
                
                // Reset the toggle-all button state
                const toggleAllButton = document.querySelector(`.toggle-all[data-target="${checkboxGroup}"]`);
                if (toggleAllButton) {
                    toggleAllButton.setAttribute('data-state', 'unchecked');
                    toggleAllButton.textContent = 'Pilih Semua';
                }
            });
        });
    }
    
    // Add hover effect to checkbox items
    const checkboxItems = document.querySelectorAll('.checkbox-item');
    if (checkboxItems) {
        checkboxItems.forEach(function(item) {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(5px)';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });
    }
    
    // Print results
    const printButton = document.getElementById('print-results');
    if (printButton) {
        printButton.addEventListener('click', function() {
            window.print();
        });
    }
    
    // Save results to PDF
    const pdfButton = document.getElementById('save-pdf');
    if (pdfButton) {
        pdfButton.addEventListener('click', function() {
            // Load jsPDF library dynamically
            if (typeof jspdf === 'undefined') {
                const script = document.createElement('script');
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
                script.onload = function() {
                    const scriptHtml2Canvas = document.createElement('script');
                    scriptHtml2Canvas.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js';
                    scriptHtml2Canvas.onload = generatePDF;
                    document.head.appendChild(scriptHtml2Canvas);
                };
                document.head.appendChild(script);
            } else {
                generatePDF();
            }
        });
    }
    
    // Function to generate PDF
    function generatePDF() {
        // Show loading indicator
        const loadingIndicator = document.createElement('div');
        loadingIndicator.className = 'loading-indicator';
        loadingIndicator.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyiapkan PDF...';
        document.body.appendChild(loadingIndicator);
        
        // Get diagnosis results element
        const resultsElement = document.getElementById('diagnosis-results');
        
        // Create a clone of the results element to modify for PDF
        const resultsClone = resultsElement.cloneNode(true);
        
        // Remove action buttons from the clone
        const actionButtons = resultsClone.querySelector('.action-buttons');
        if (actionButtons) {
            actionButtons.remove();
        }
        
        // Remove user info form from the clone
        const userInfoForm = resultsClone.querySelector('#user-info-form');
        if (userInfoForm) {
            userInfoForm.remove();
        }
        
        // Apply PDF-specific styling
        resultsClone.style.padding = '20px';
        resultsClone.style.width = '100%';
        resultsClone.style.maxWidth = '800px';
        resultsClone.style.margin = '0 auto';
        resultsClone.style.backgroundColor = 'white';
        resultsClone.style.fontFamily = 'Arial, sans-serif';
        
        // Temporarily append the clone to the document
        resultsClone.style.position = 'absolute';
        resultsClone.style.left = '-9999px';
        document.body.appendChild(resultsClone);
        
        // Get current date for filename
        const now = new Date();
        const dateStr = now.getFullYear() + '' + 
                        String(now.getMonth() + 1).padStart(2, '0') + '' + 
                        String(now.getDate()).padStart(2, '0');
        const timeStr = String(now.getHours()).padStart(2, '0') + '' + 
                        String(now.getMinutes()).padStart(2, '0');
        const filename = 'diagnosis_kulit_' + dateStr + '_' + timeStr + '.pdf';
        
        // Use html2canvas to capture the element as an image
        html2canvas(resultsClone, {
            scale: 2,
            useCORS: true,
            logging: false,
            backgroundColor: '#ffffff'
        }).then(function(canvas) {
            // Remove the temporary clone
            document.body.removeChild(resultsClone);
            
            // Create PDF
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF('p', 'mm', 'a4');
            
            // Add header
            pdf.setFontSize(18);
            pdf.setTextColor(123, 158, 168); // Primary color
            pdf.text('Sistem Pakar Perawatan Kulit', 105, 20, { align: 'center' });
            
            pdf.setFontSize(14);
            pdf.setTextColor(93, 110, 126); // Dark color
            pdf.text('Hasil Diagnosis', 105, 30, { align: 'center' });
            
            // Add date
            pdf.setFontSize(10);
            pdf.setTextColor(100, 100, 100);
            pdf.text('Tanggal: ' + now.toLocaleDateString('id-ID'), 105, 40, { align: 'center' });
            
            // Get the image from canvas and add to PDF
            const imgData = canvas.toDataURL('image/png');
            const imgProps = pdf.getImageProperties(imgData);
            const pdfWidth = pdf.internal.pageSize.getWidth() - 40;
            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
            
            pdf.addImage(imgData, 'PNG', 20, 50, pdfWidth, pdfHeight);
            
            // Add footer
            const pageCount = pdf.internal.getNumberOfPages();
            pdf.setFontSize(8);
            pdf.setTextColor(150, 150, 150);
            for (let i = 1; i <= pageCount; i++) {
                pdf.setPage(i);
                pdf.text('Halaman ' + i + ' dari ' + pageCount, 105, pdf.internal.pageSize.getHeight() - 10, { align: 'center' });
                pdf.text('© ' + new Date().getFullYear() + ' Sistem Pakar Perawatan Kulit', 105, pdf.internal.pageSize.getHeight() - 5, { align: 'center' });
            }
            
            // Save PDF
            pdf.save(filename);
            
            // Remove loading indicator
            document.body.removeChild(loadingIndicator);
        }).catch(function(error) {
            console.error('Error generating PDF:', error);
            alert('Terjadi kesalahan saat membuat PDF. Silakan coba lagi.');
            document.body.removeChild(loadingIndicator);
        });
    }
    
    // Show/hide user info form
    const saveResultsButton = document.getElementById('save-results');
    const userInfoForm = document.getElementById('user-info-form');
    
    if (saveResultsButton && userInfoForm) {
        saveResultsButton.addEventListener('click', function() {
            userInfoForm.style.display = userInfoForm.style.display === 'none' ? 'block' : 'none';
            
            // Scroll to the form
            if (userInfoForm.style.display === 'block') {
                userInfoForm.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }
}); 