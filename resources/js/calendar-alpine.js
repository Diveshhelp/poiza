// resources/js/calendar-alpine.js

document.addEventListener('alpine:init', () => {
    // You can register a global Alpine component if needed
    Alpine.data('calendarApp', () => ({
        // Calendar state
        currentView: 'month', // month, week, day
        isDragging: false,
        draggedEvent: null,
        
        // Event state
        showEventDetails: false,
        currentEvent: null,
        
        // Event utility functions
        formatDate(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleDateString('en-US', {
                weekday: 'short',
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        },
        
        getEventColor(type) {
            const colorMap = {
                'general': 'gray',
                'meeting': 'green',
                'deadline': 'red',
                'personal': 'purple'
            };
            return colorMap[type] || 'gray';
        },
        
        // Event handling
        viewEvent(event) {
            this.currentEvent = event;
            this.showEventDetails = true;
        },
        
        startDrag(event) {
            if (!this.isDraggingEnabled) return;
            
            this.isDragging = true;
            this.draggedEvent = event;
            
            // Add a dragging class for styling
            event.target.classList.add('dragging');
        },
        
        onDragOver(date) {
            if (!this.isDragging || !this.draggedEvent) return;
            
            // Visual feedback for the target date cell
            // This would be implemented in the actual component
        },
        
        onDrop(date) {
            if (!this.isDragging || !this.draggedEvent) return;
            
            // This would emit a Livewire event to update the event date in the backend
            this.$wire.call('moveEvent', this.draggedEvent.id, date);
            
            this.isDragging = false;
            this.draggedEvent = null;
        },
        
        // Calendar view switching
        switchView(view) {
            this.currentView = view;
            
            // This would trigger changes to the display in a full implementation
            // For example, recalculating the visible date range
        },
        
        // Initialize the calendar with additional features
        init() {
            // Set up event listeners for drag and drop if needed
            this.isDraggingEnabled = true;
            
            // Listen for Livewire events
            Livewire.on('eventAdded', () => {
                // Close modal and reset form after event is added
                this.showEventModal = false;
                this.newEvent = {title: '', description: '', type: 'general'};
            });
            
            Livewire.on('eventUpdated', () => {
                // Close details modal after event is updated
                this.showEventDetails = false;
            });
        }
    }));
});