 // Helper functions for your Alpine.js component
 function formatReadableDate(dateString) {
    if (!dateString) return '';
    
    // If it's already in a readable format, return as is
    if (typeof dateString === 'string' && !dateString.includes('T')) {
        return dateString;
    }
    
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return dateString;
        
        // Format as "Wednesday, March 26, 2025"
        return date.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    } catch (error) {
        return dateString || '';
    }
}

function extractTimeFromIso(dateString) {
    if (!dateString) return '';
    
    // If it's already just a time, return as is
    if (dateString.includes(':') && !dateString.includes('-')) {
        return dateString;
    }
    
    // For ISO format 2025-03-25T19:20:00.000000Z
    if (dateString.includes('T')) {
        try {
            // Extract time portion and format it
            const timePart = dateString.split('T')[1].substring(0, 5);
            
            // Convert 24-hour time to 12-hour format with AM/PM
            const hourNum = parseInt(timePart.split(':')[0], 10);
            const minutes = timePart.split(':')[1];
            const ampm = hourNum >= 12 ? 'PM' : 'AM';
            const hour12 = hourNum % 12 || 12;
            
            return `${hour12}:${minutes} ${ampm}`;
        } catch (error) {
            return '';
        }
    }
    
    return '';
}