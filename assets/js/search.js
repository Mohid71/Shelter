// Search and Filter Functionality for Tables

// Generic table search function
function searchTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const filter = input.value.toUpperCase();
    const table = document.getElementById(tableId);
    const tr = table.getElementsByTagName('tr');

    // Loop through all table rows (skip header)
    for (let i = 1; i < tr.length; i++) {
        let found = false;
        const td = tr[i].getElementsByTagName('td');
        
        // Search through all columns
        for (let j = 0; j < td.length; j++) {
            if (td[j]) {
                const txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }
        
        tr[i].style.display = found ? '' : 'none';
    }
}

// Filter table by specific column
function filterTableByColumn(selectId, tableId, columnIndex) {
    const select = document.getElementById(selectId);
    const filterValue = select.value.toUpperCase();
    const table = document.getElementById(tableId);
    const tr = table.getElementsByTagName('tr');

    // Loop through all table rows (skip header)
    for (let i = 1; i < tr.length; i++) {
        const td = tr[i].getElementsByTagName('td')[columnIndex];
        
        if (td) {
            const txtValue = td.textContent || td.innerText;
            if (filterValue === '' || txtValue.toUpperCase().indexOf(filterValue) > -1) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }
}

// Filter by date range
function filterByDateRange(tableId, columnIndex, startDateId, endDateId) {
    const startDate = document.getElementById(startDateId).value;
    const endDate = document.getElementById(endDateId).value;
    const table = document.getElementById(tableId);
    const tr = table.getElementsByTagName('tr');

    if (!startDate && !endDate) {
        // Show all if no dates selected
        for (let i = 1; i < tr.length; i++) {
            tr[i].style.display = '';
        }
        return;
    }

    for (let i = 1; i < tr.length; i++) {
        const td = tr[i].getElementsByTagName('td')[columnIndex];
        
        if (td) {
            const dateValue = td.textContent || td.innerText;
            const rowDate = new Date(dateValue);
            
            let show = true;
            
            if (startDate && rowDate < new Date(startDate)) {
                show = false;
            }
            if (endDate && rowDate > new Date(endDate)) {
                show = false;
            }
            
            tr[i].style.display = show ? '' : 'none';
        }
    }
}

// Reset all filters
function resetFilters(tableId) {
    const table = document.getElementById(tableId);
    const tr = table.getElementsByTagName('tr');
    
    // Show all rows
    for (let i = 1; i < tr.length; i++) {
        tr[i].style.display = '';
    }
    
    // Clear all filter inputs on the page
    const inputs = document.querySelectorAll('input[type="text"], input[type="date"], select');
    inputs.forEach(input => {
        if (input.id && input.id.includes('filter')) {
            input.value = '';
        }
    });
}

// Export filtered table to CSV
function exportFilteredToCSV(tableId, filename) {
    const table = document.getElementById(tableId);
    const rows = table.getElementsByTagName('tr');
    let csv = [];
    
    for (let i = 0; i < rows.length; i++) {
        // Only export visible rows
        if (rows[i].style.display !== 'none') {
            const cols = rows[i].querySelectorAll('td, th');
            let row = [];
            
            for (let j = 0; j < cols.length - 1; j++) { // Skip last column (Actions)
                let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ');
                data = data.replace(/"/g, '""');
                row.push('"' + data + '"');
            }
            
            csv.push(row.join(','));
        }
    }
    
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.setAttribute('hidden', '');
    a.setAttribute('href', url);
    a.setAttribute('download', filename + '.csv');
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

// Count visible rows
function updateRowCount(tableId, countElementId) {
    const table = document.getElementById(tableId);
    const tr = table.getElementsByTagName('tr');
    let visibleCount = 0;
    
    for (let i = 1; i < tr.length; i++) {
        if (tr[i].style.display !== 'none') {
            visibleCount++;
        }
    }
    
    const countElement = document.getElementById(countElementId);
    if (countElement) {
        countElement.textContent = `Showing ${visibleCount} of ${tr.length - 1} records`;
    }
}
