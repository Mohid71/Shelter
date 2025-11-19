// Table Sorting Functionality

function sortTable(tableId, columnIndex, sortType = 'string') {
    const table = document.getElementById(tableId);
    const tbody = table.getElementsByTagName('tbody')[0];
    const rows = Array.from(tbody.getElementsByTagName('tr'));
    
    // Sort rows based on column
    rows.sort((a, b) => {
        const aValue = a.getElementsByTagName('td')[columnIndex]?.textContent || '';
        const bValue = b.getElementsByTagName('td')[columnIndex]?.textContent || '';
        
        if (sortType === 'number') {
            return parseFloat(aValue) - parseFloat(bValue);
        } else if (sortType === 'date') {
            return new Date(aValue) - new Date(bValue);
        } else {
            return aValue.localeCompare(bValue);
        }
    });
    
    // Re-append sorted rows
    rows.forEach(row => tbody.appendChild(row));
}

function sortTableBySelect(selectId, tableId) {
    const select = document.getElementById(selectId);
    const value = select.value;
    
    if (!value) {
        // Reset to original order - reload page
        location.reload();
        return;
    }
    
    const [columnIndex, sortType] = value.split('|');
    sortTable(tableId, parseInt(columnIndex), sortType);
}
