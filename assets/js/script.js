document.addEventListener("DOMContentLoaded", function () {
    
    // 1. Client-Side Validation
    const forms = document.querySelectorAll("form");
    
    forms.forEach(form => {
        form.addEventListener("submit", function (e) {
            
            let isValid = true;
            let errorMessage = "";

            // Required fields
            const inputs = form.querySelectorAll("input:not([type='button']):not([type='submit']):not([type='reset']), textarea, select");
            inputs.forEach(input => {
                if (!input.value.trim() && input.type !== 'hidden') {
                    isValid = false;
                    input.style.borderColor = "red";
                } else {
                    input.style.borderColor = "#ccc";
                }
            });

            // Email validation
            const emails = form.querySelectorAll("input[type='email']");
            emails.forEach(email => {
                if (email.value.trim() !== "") {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(email.value)) {
                        isValid = false;
                        errorMessage += "- Invalid email format.\n";
                        email.style.borderColor = "red";
                    }
                }
            });

            // Phone validation
            const phones = form.querySelectorAll("input[type='tel']");
            phones.forEach(phone => {
                if (phone.value.trim() !== "") {
                    const phoneRegex = /^\d+$/;
                    if (!phoneRegex.test(phone.value)) {
                        isValid = false;
                        errorMessage += "- Phone number must contain digits only.\n";
                        phone.style.borderColor = "red";
                    }
                }
            });

            // Date validation dd-mm-yyyy
            const textDates = form.querySelectorAll("input[placeholder*='dd-mm-yyyy']");
            textDates.forEach(date => {
                 if (date.value.trim() !== "") {
                     const dateRegex = /^\d{2}-\d{2}-\d{4}$/;
                     if(!dateRegex.test(date.value)) {
                         isValid = false;
                         errorMessage += "- Date must be in dd-mm-yyyy format.\n";
                         date.style.borderColor = "red";
                     }
                 }
            });

            if (!isValid) {
                e.preventDefault();
                alert("Validation Failed:\n" + errorMessage + (errorMessage === "" ? "- Please fill in all required fields.\n" : ""));
                return;
            }

            // Simple toast/alert on success
            
            
            // For mock purpose, clear the form
            
        });
    });

    // Reset button logic
    const resetButtons = document.querySelectorAll("button[type='reset']");
    resetButtons.forEach(btn => {
        btn.addEventListener("click", function () {
            const form = this.closest("form");
            if (form) {
                // setTimeout allows the native reset to clear values first
                setTimeout(() => {
                    const inputs = form.querySelectorAll("input, textarea, select");
                    inputs.forEach(input => {
                        input.style.borderColor = "#ccc";
                    });
                }, 10);
            }
        });
    });

    // 2. Dynamic Dropdowns
    const paperStatuses = ["Submitted", "Under Review", "Accepted", "Rejected", "Revision Required"];
    const reviewRecommendations = ["Accept", "Minor Revision", "Major Revision", "Reject"];
    const finalStatuses = ["Accepted", "Rejected", "Further Revision"];

    function populateDropdown(id, options, defaultText) {
        const select = document.getElementById(id);
        if (select) {
            if (select.tagName.toLowerCase() === 'input') {
                const newSelect = document.createElement('select');
                newSelect.id = select.id;
                newSelect.name = select.name;
                newSelect.className = select.className;
                select.parentNode.replaceChild(newSelect, select);
                populateOptions(newSelect, options, defaultText);
            } else {
                populateOptions(select, options, defaultText);
            }
        }
    }

    function populateOptions(selectElement, options, defaultText) {
        selectElement.innerHTML = `<option value="">${defaultText}</option>`;
        options.forEach(opt => {
            selectElement.innerHTML += `<option value="${opt}">${opt}</option>`;
        });
    }

    populateDropdown("status", paperStatuses, "Select status");
    populateDropdown("recommendation", reviewRecommendations, "Select recommendation");
    populateDropdown("final_status", finalStatuses, "Select final status");
});

// Auto-format status text into UI badges in data tables
document.addEventListener("DOMContentLoaded", function() {
    const cells = document.querySelectorAll("table td");
    cells.forEach(cell => {
        const text = cell.innerText.trim();
        const statuses = ["Submitted", "Under Review", "Accepted", "Rejected", "Revision Required", "Further Revision", "Accept", "Minor Revision", "Major Revision", "Reject"];
        
        if (statuses.includes(text)) {
            let badgeClass = "";
            if (text === "Submitted") badgeClass = "badge-submitted";
            else if (text === "Under Review") badgeClass = "badge-review";
            else if (text === "Accepted" || text === "Accept") badgeClass = "badge-accepted";
            else if (text === "Rejected" || text === "Reject") badgeClass = "badge-rejected";
            else if (text === "Revision Required" || text === "Further Revision" || text === "Minor Revision" || text === "Major Revision") badgeClass = "badge-revision";
            
            if (badgeClass) {
                cell.innerHTML = `<span class="badge ${badgeClass}">${text}</span>`;
            }
        }
    });
});
