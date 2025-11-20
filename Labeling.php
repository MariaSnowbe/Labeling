<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Labeling Tool</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        
        .container {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        
        .header {
            background-color: #ddd;
            color: black;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ccc;
        }
        
        .main {
            display: flex;
            flex: 1;
            overflow: hidden;
        }
        
        .sidebar {
            width: 250px;
            background-color: #e8e8e8;
            color: black;
            padding: 15px;
            overflow-y: auto;
            transition: transform 0.3s ease;
            border-right: 1px solid #ccc;
        }
        
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: #fff;
            overflow: hidden;
        }
        
        .toolbar {
            padding: 10px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            background-color: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }
        
        button {
            padding: 8px 12px;
            background-color: #ae00eeff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            min-width: 60px;
            touch-action: manipulation;
        }
        
        button:hover {
            background-color: #ae00d1ff;
        }
        
        button.active {
            background-color: #363738ff;
        }
        
        .canvas-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: auto;
            position: relative;
            touch-action: none;
        }
        
        #labelingCanvas {
            max-width: calc(100vw - 20px);
            max-height: calc(100vh - 200px);
            background-color: #eee;
            cursor: crosshair;
            touch-action: none;
        }
        
        .file-list {
            margin-top: 20px;
        }
        
        .file-item {
            padding: 12px;
            margin-bottom: 5px;
            background-color: #d0d0d0;
            border-radius: 4px;
            cursor: pointer;
            color: black;
            border: 1px solid #bbb;
        }
        
        .file-item:hover {
            background-color: #c0c0c0;
        }
        
        .file-item.active {
            background-color: #4CAF50;
            color: white;
        }
        
        .label-list {
            margin-top: 20px;
        }
        
        .label-item {
            display: flex;
            align-items: center;
            padding: 12px;
            margin-bottom: 5px;
            background-color: #d0d0d0;
            border-radius: 4px;
            cursor: pointer;
            border: 1px solid #bbb;
            touch-action: manipulation;
        }
        
        .label-item:hover {
            background-color: #84dbfbff;
        }
        
        .label-item.active {
            background-color: #00adee;
            color: white;
        }
        
        .label-color {
            width: 15px;
            height: 15px;
            margin-right: 10px;
            border-radius: 3px;
            border: 1px solid #999;
        }
        
        .label-name {
            flex: 1;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 100;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            width: 400px;
            max-width: 90%;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input, select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        #statusBar {
            background-color: #333;
            color: white;
            padding: 5px 20px;
            font-size: 14px;
        }
        
        .label-category {
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: bold;
            color: black;
            background-color: #bbb;
            padding: 5px;
            border-radius: 3px;
        }

        /* Header logo container */
        .header-logo {
            display: flex;
            align-items: center;
        }

        .header-logo img {
            height: 100%;
            width: 250px;
        }

        /* Mobile menu styles */
        .mobile-menu-toggle {
            display: none;
            background-color: #00adee;
            color: white;
            border: none;
            padding: 12px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            touch-action: manipulation;
            min-width: 50px;
            height: 44px;
        }

        .mobile-menu-toggle:hover {
            background-color: #0096d1;
        }

        .mobile-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 90;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: inline-block;
            }

            .header {
                padding: 10px 15px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .header-logo img {
                width: 150px !important;
                height: auto !important;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                z-index: 95;
                transform: translateX(-100%);
                width: 280px;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
                background-color: #e8e8e8;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .mobile-overlay.show {
                display: block;
            }

            .toolbar {
                padding: 8px;
                gap: 5px;
                justify-content: flex-start;
                overflow-x: auto;
                white-space: nowrap;
            }

            .toolbar button {
                padding: 10px 8px;
                font-size: 12px;
                min-width: 50px;
                flex-shrink: 0;
            }

            .main {
                padding-left: 0;
            }

            .canvas-container {
                padding: 5px;
            }

            #labelingCanvas {
                max-width: calc(100vw - 10px);
                max-height: calc(100vh - 180px);
                width: auto;
                height: auto;
            }

            .modal-content {
                width: 95%;
                margin: 10px;
            }

            /* Better touch targets */
            .label-item, .file-item {
                padding: 15px;
                margin-bottom: 8px;
            }

            button {
                min-height: 44px;
                padding: 10px 12px;
            }
        }

        @media (max-width: 480px) {
            .header {
                padding: 8px 12px;
            }

            .header-logo img {
                width: 120px !important;
                height: auto !important;
            }

            .mobile-menu-toggle {
                padding: 10px 14px;
                font-size: 16px;
            }

            .toolbar {
                justify-content: flex-start;
                padding: 5px;
            }

            .toolbar button {
                padding: 12px 6px;
                font-size: 11px;
                min-width: 45px;
            }

            #labelingCanvas {
                max-width: calc(100vw - 10px);
                max-height: calc(100vh - 160px);
            }
        }

        /* Close button for mobile sidebar */
        .sidebar-close {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #ff4444;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            cursor: pointer;
            font-size: 12px;
            display: none;
            touch-action: manipulation;
        }

        @media (max-width: 768px) {
            .sidebar-close {
                display: block;
            }
        }

        /* Touch-friendly canvas styles */
        @media (max-width: 768px) {
            .canvas-container {
                overflow: hidden;
                position: relative;
            }
            
            #labelingCanvas {
                position: relative;
                display: block;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-logo">
                <!-- <img src="makeclouds.png" alt="MakeClouds"> -->
            </div>
            <button class="mobile-menu-toggle" id="mobileMenuToggle">☰</button>
        </div>
  
        <div class="main">
            <div class="mobile-overlay" id="mobileOverlay"></div>
            <div class="sidebar" id="sidebar">
                <button class="sidebar-close" id="sidebarClose">×</button>
                <h3>Files</h3>
                <div>
                    <button id="openFileBtn">Open File</button>
                    <button id="openDirBtn">Open Directory</button>
                </div>
                <div class="file-list" id="fileList">
                    <!-- Files will be listed here -->
                </div>
                
                <h3>Labels</h3>
                <div>
                    <button id="addLabelBtn">Add Label</button>
                    <button id="selectLabelBtn">Select Label</button>
                </div>
                <div class="label-list" id="labelList">
                    <div class="label-category">Animals</div>
                    <!-- Animal labels will be added here -->
                    
                    <div class="label-category">People</div>
                    <!-- People labels will be added here -->
                    
                    <div class="label-category">Objects</div>
                    <!-- Object labels will be added here -->
                </div>
            </div>
            
            <div class="content">
                <div class="toolbar">
                    <button id="selectBtn" class="active">Select</button>
                    <button id="rectBtn">Rectangle</button>
                    <button id="polygonBtn">Polygon</button>
                    <button id="textBtn">Text</button>
                    <button id="deleteBtn">Delete All</button>
                    <button id="undoBtn">Undo</button>
                    <button id="redoBtn">Redo</button>
                    <button id="prevBtn">Previous</button>
                    <button id="nextBtn">Next</button>
                    <button id="exportBtn">Export</button>
                    <button id="saveBtn">Save</button>
                    <button id="exportImagesBtn">Export Images</button>
                </div>
                
                <div class="canvas-container">
                    <canvas id="labelingCanvas"></canvas>
                </div>
                
                <div id="statusBar">
                    Ready
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Label Modal -->
    <div class="modal" id="labelModal">
        <div class="modal-content">
            <h2>Add New Label</h2>
            <div class="form-group">
                <label for="labelName">Label Name</label>
                <input type="text" id="labelName" placeholder="Enter label name">
            </div>
            <div class="form-group">
                <label for="labelColor">Label Color</label>
                <input type="color" id="labelColor" value="#FF0000">
            </div>
            <div class="form-group">
                <label for="labelCategory">Category</label>
                <select id="labelCategory">
                    <option value="animals">Animals</option>
                    <option value="people">People</option>
                    <option value="objects">Objects</option>
                </select>
            </div>
            <div class="modal-actions">
                <button id="cancelLabelBtn">Cancel</button>
                <button id="saveLabelBtn">Save</button>
            </div>
        </div>
    </div>
    
    <!-- Select Label Modal -->
    <div class="modal" id="selectLabelModal">
        <div class="modal-content">
            <h2>Select Label</h2>
            <div class="label-list" id="selectLabelList">
                <!-- Labels will be listed here for selection -->
            </div>
            <div class="modal-actions">
                <button id="cancelSelectLabelBtn">Cancel</button>
            </div>
        </div>
    </div>
    
    <!-- Export Modal -->
    <div class="modal" id="exportModal">
        <div class="modal-content">
            <h2>Export Labels</h2>
            <div class="form-group">
                <label for="exportFormat">Format</label>
                <select id="exportFormat">
                    <option value="json">JSON</option>
                    <option value="xml">Pascal VOC XML</option>
                    <option value="yolo">YOLO TXT</option>
                    <option value="csv">CSV</option>
                </select>
            </div>
            <div class="modal-actions">
                <button id="cancelExportBtn">Cancel</button>
                <button id="confirmExportBtn">Export</button>
            </div>
        </div>
    </div>
    
    <script>
        // Global variables
        let currentImage = null;
        let currentImageIndex = -1;
        let images = [];
        let labels = [];
        let currentLabelId = 1;
        let annotations = [];
        let allAnnotations = {}; // Stores annotations for all images
        let currentTool = 'select';
        let isDrawing = false;
        let currentShape = null;
        let tempPoints = [];
        let selectedAnnotation = null;
        let selectedLabel = null;
        let lastMouseX = 0;
        let lastMouseY = 0;
        let isMobile = window.innerWidth <= 768;
        let touchStartTime = 0;
        let lastTouchTime = 0;
        let isPolygonMode = false;
        
        // Undo/Redo functionality
        let undoStack = [];
        let redoStack = [];
        const MAX_UNDO_STEPS = 50;
        
        // DOM elements
        const canvas = document.getElementById('labelingCanvas');
        const ctx = canvas.getContext('2d');
        const fileList = document.getElementById('fileList');
        const labelList = document.getElementById('labelList');
        const statusBar = document.getElementById('statusBar');
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        
        // Tool buttons
        const selectBtn = document.getElementById('selectBtn');
        const rectBtn = document.getElementById('rectBtn');
        const polygonBtn = document.getElementById('polygonBtn');
        const textBtn = document.getElementById('textBtn');
        const deleteBtn = document.getElementById('deleteBtn');
        const undoBtn = document.getElementById('undoBtn');
        const redoBtn = document.getElementById('redoBtn');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        
        // Modal elements
        const labelModal = document.getElementById('labelModal');
        const selectLabelModal = document.getElementById('selectLabelModal');
        const exportModal = document.getElementById('exportModal');
        const labelNameInput = document.getElementById('labelName');
        const labelColorInput = document.getElementById('labelColor');
        const labelCategoryInput = document.getElementById('labelCategory');
        const exportFormatSelect = document.getElementById('exportFormat');
        const selectLabelList = document.getElementById('selectLabelList');
        
        // Initialize
        function init() {
            // Detect mobile
            isMobile = window.innerWidth <= 768;
            window.addEventListener('resize', () => {
                isMobile = window.innerWidth <= 768;
            });

            // Mobile menu events
            mobileMenuToggle.addEventListener('click', openMobileMenu);
            sidebarClose.addEventListener('click', closeMobileMenu);
            mobileOverlay.addEventListener('click', closeMobileMenu);
            
            // Event listeners for buttons
            document.getElementById('openFileBtn').addEventListener('click', openFile);
            document.getElementById('openDirBtn').addEventListener('click', openDirectory);
            document.getElementById('addLabelBtn').addEventListener('click', showAddLabelModal);
            document.getElementById('selectLabelBtn').addEventListener('click', showSelectLabelModal);
            document.getElementById('saveLabelBtn').addEventListener('click', saveLabel);
            document.getElementById('cancelLabelBtn').addEventListener('click', hideAddLabelModal);
            document.getElementById('cancelSelectLabelBtn').addEventListener('click', hideSelectLabelModal);
            document.getElementById('exportBtn').addEventListener('click', showExportModal);
            document.getElementById('confirmExportBtn').addEventListener('click', exportLabels);
            document.getElementById('cancelExportBtn').addEventListener('click', hideExportModal);
            document.getElementById('saveBtn').addEventListener('click', saveProject);
            document.getElementById('exportImagesBtn').addEventListener('click', exportLabeledImages);
            
            // Tool buttons
            selectBtn.addEventListener('click', () => setTool('select'));
            rectBtn.addEventListener('click', () => setTool('rect'));
            polygonBtn.addEventListener('click', () => setTool('polygon'));
            textBtn.addEventListener('click', () => setTool('text'));
            deleteBtn.addEventListener('click', deleteAllLabelsInImage);
            undoBtn.addEventListener('click', undo);
            redoBtn.addEventListener('click', redo);
            prevBtn.addEventListener('click', prevImage);
            nextBtn.addEventListener('click', nextImage);
            
            // Canvas events - both mouse and touch
            setupCanvasEvents();
            
            // Keyboard shortcuts
            document.addEventListener('keydown', handleKeyboard);
            
            // Add default labels for different categories
            addLabel('Bird', '#FF0000', 'animals');
            addLabel('Dog', '#00FF00', 'animals');
            addLabel('Sheep', '#0000FF', 'animals');
            addLabel('Man', '#FFFF00', 'people');
            addLabel('Woman', '#FF00FF', 'people');
            addLabel('Child', '#00FFFF', 'people');
            addLabel('Car', '#FFA500', 'objects');
            addLabel('House', '#800080', 'objects');
            addLabel('Tree', '#008000', 'objects');
            
            updateStatus('Ready - Select a label and tool to start');
            updateUndoRedoButtons();
        }

        // Setup canvas events for both mouse and touch
        function setupCanvasEvents() {
            // Mouse events
            canvas.addEventListener('mousedown', handleStart);
            canvas.addEventListener('mousemove', handleMove);
            canvas.addEventListener('mouseup', handleEnd);
            canvas.addEventListener('mouseleave', handleEnd);
            canvas.addEventListener('dblclick', handleDoubleClick);
            
            // Touch events
            canvas.addEventListener('touchstart', handleTouchStart, { passive: false });
            canvas.addEventListener('touchmove', handleTouchMove, { passive: false });
            canvas.addEventListener('touchend', handleTouchEnd, { passive: false });
            
            // Prevent default touch behavior
            canvas.addEventListener('touchstart', (e) => e.preventDefault());
            canvas.addEventListener('touchmove', (e) => e.preventDefault());
            canvas.addEventListener('touchend', (e) => e.preventDefault());
        }

        // Get coordinates from mouse or touch event
        function getEventCoordinates(e) {
            const rect = canvas.getBoundingClientRect();
            let clientX, clientY;
            
            if (e.touches && e.touches.length > 0) {
                clientX = e.touches[0].clientX;
                clientY = e.touches[0].clientY;
            } else if (e.changedTouches && e.changedTouches.length > 0) {
                clientX = e.changedTouches[0].clientX;
                clientY = e.changedTouches[0].clientY;
            } else {
                clientX = e.clientX;
                clientY = e.clientY;
            }
            
            return {
                x: (clientX - rect.left) * (canvas.width / rect.width),
                y: (clientY - rect.top) * (canvas.height / rect.height)
            };
        }

        // Touch event handlers with improved polygon support
        function handleTouchStart(e) {
            e.preventDefault();
            touchStartTime = Date.now();
            const coords = getEventCoordinates(e);
            
            // For polygon mode, handle different touch patterns
            if (currentTool === 'polygon' && isPolygonMode) {
                // Check for double tap (tap within 300ms of previous tap)
                const now = Date.now();
                if (now - lastTouchTime < 300 && tempPoints.length >= 3) {
                    finishPolygon();
                    return;
                }
                lastTouchTime = now;
            }
            
            handleStart({
                clientX: coords.x + canvas.getBoundingClientRect().left,
                clientY: coords.y + canvas.getBoundingClientRect().top
            });
        }

        function handleTouchMove(e) {
            e.preventDefault();
            const coords = getEventCoordinates(e);
            handleMove({
                clientX: coords.x + canvas.getBoundingClientRect().left,
                clientY: coords.y + canvas.getBoundingClientRect().top
            });
        }

        function handleTouchEnd(e) {
            e.preventDefault();
            const coords = getEventCoordinates(e);
            handleEnd({
                clientX: coords.x + canvas.getBoundingClientRect().left,
                clientY: coords.y + canvas.getBoundingClientRect().top
            });
        }

        // Unified start handler
        function handleStart(e) {
            if (!currentImage) return;
            
            const coords = getEventCoordinates(e);
            
            if (currentTool === 'select') {
                selectedAnnotation = getAnnotationAtPoint(coords.x, coords.y);
                
                if (selectedAnnotation) {
                    tempPoints = JSON.parse(JSON.stringify(selectedAnnotation.points));
                    isDrawing = true;
                }
                
                redrawCanvas();
                return;
            }
            
            if (!selectedLabel) {
                updateStatus('Please select a label first');
                return;
            }
            
            // Save state before drawing (except for polygon continuation)
            if (currentTool !== 'polygon' || !isPolygonMode) {
                saveState();
            }
            
            switch (currentTool) {
                case 'rect':
                    isDrawing = true;
                    currentShape = 'rect';
                    tempPoints = [{ x: coords.x, y: coords.y }, { x: coords.x, y: coords.y }];
                    break;
                    
                case 'polygon':
                    handlePolygonStart(coords);
                    break;
                    
                case 'text':
                    currentShape = 'text';
                    const text = prompt('Enter text:');
                    if (text) {
                        annotations.push({
                            id: Date.now(),
                            labelId: selectedLabel.id,
                            shape: 'text',
                            points: [{ x: coords.x, y: coords.y }],
                            text: text
                        });
                        redrawCanvas();
                        updateStatus(`Added text label: ${selectedLabel.name}`);
                    }
                    break;
            }
        }

        // Handle polygon start with proper state management
        function handlePolygonStart(coords) {
            if (!isPolygonMode) {
                // Starting new polygon
                isPolygonMode = true;
                currentShape = 'polygon';
                tempPoints = [{ x: coords.x, y: coords.y }];
                updateStatus(`Polygon started. Click to add points, double-click or tap twice quickly to finish (min 3 points)`);
            } else {
                // Adding point to existing polygon
                tempPoints.push({ x: coords.x, y: coords.y });
                updateStatus(`Polygon points: ${tempPoints.length}. Double-click or tap twice quickly to finish (min 3 points)`);
            }
            redrawCanvas();
        }

        // Unified move handler
        function handleMove(e) {
            if (!currentImage) return;
            
            const coords = getEventCoordinates(e);
            lastMouseX = e.clientX;
            lastMouseY = e.clientY;
            
            if (currentTool === 'select' && isDrawing && selectedAnnotation) {
                // Move the selected annotation
                const dx = coords.x - tempPoints[0].x;
                const dy = coords.y - tempPoints[0].y;
                
                selectedAnnotation.points = tempPoints.map(p => ({
                    x: p.x + dx,
                    y: p.y + dy
                }));
                
                tempPoints[0] = { x: coords.x, y: coords.y };
                redrawCanvas();
                return;
            }
            
            if (isDrawing && currentShape === 'rect') {
                tempPoints[1] = { x: coords.x, y: coords.y };
                redrawCanvas();
            }
            
            // For polygon mode, always redraw to show preview line
            if (isPolygonMode) {
                redrawCanvas();
            }
        }

        // Unified end handler
        function handleEnd(e) {
            if (!currentImage) return;
            
            if (currentTool === 'select') {
                isDrawing = false;
                tempPoints = [];
                return;
            }
            
            if (currentTool === 'polygon') {
                // For polygon, we don't end on mouseup/touchend
                // We continue adding points until double-click or explicit finish
                return;
            }
            
            const coords = getEventCoordinates(e);
            
            if (currentShape === 'rect' && isDrawing) {
                // Ensure minimum rectangle size
                const dx = Math.abs(tempPoints[1].x - tempPoints[0].x);
                const dy = Math.abs(tempPoints[1].y - tempPoints[0].y);
                
                if (dx > 5 && dy > 5) {
                    annotations.push({
                        id: Date.now(),
                        labelId: selectedLabel.id,
                        shape: 'rect',
                        points: [...tempPoints]
                    });
                    updateStatus(`Added rectangle label: ${selectedLabel.name}`);
                } else {
                    updateStatus('Rectangle too small, try again');
                }
                
                isDrawing = false;
                tempPoints = [];
                currentShape = null;
                redrawCanvas();
            }
        }

        // Handle double click for polygon completion
        function handleDoubleClick(e) {
            if (currentTool === 'polygon' && isPolygonMode) {
                finishPolygon();
            }
        }

        // Finish polygon creation
        function finishPolygon() {
            if (tempPoints.length >= 3) {
                annotations.push({
                    id: Date.now(),
                    labelId: selectedLabel.id,
                    shape: 'polygon',
                    points: [...tempPoints]
                });
                updateStatus(`Added polygon label: ${selectedLabel.name} (${tempPoints.length} points)`);
            } else {
                updateStatus('Polygon needs at least 3 points');
            }
            
            // Reset polygon mode
            isPolygonMode = false;
            tempPoints = [];
            currentShape = null;
            redrawCanvas();
        }
        
        // Mobile menu functions
        function openMobileMenu() {
            sidebar.classList.add('open');
            mobileOverlay.classList.add('show');
        }
        
        function closeMobileMenu() {
            sidebar.classList.remove('open');
            mobileOverlay.classList.remove('show');
        }
        
        // Keyboard shortcuts
        function handleKeyboard(e) {
            // Escape key to cancel polygon mode
            if (e.key === 'Escape' && isPolygonMode) {
                isPolygonMode = false;
                tempPoints = [];
                currentShape = null;
                redrawCanvas();
                updateStatus('Polygon creation cancelled');
                return;
            }
            
            // Enter key to finish polygon
            if (e.key === 'Enter' && isPolygonMode) {
                finishPolygon();
                return;
            }
            
            if (e.ctrlKey || e.metaKey) {
                switch (e.key) {
                    case 'z':
                        e.preventDefault();
                        if (e.shiftKey) {
                            redo();
                        } else {
                            undo();
                        }
                        break;
                    case 'y':
                        e.preventDefault();
                        redo();
                        break;
                }
            }
            
            // Tool shortcuts
            switch (e.key) {
                case 'v':
                    setTool('select');
                    break;
                case 'r':
                    setTool('rect');
                    break;
                case 'p':
                    setTool('polygon');
                    break;
                case 't':
                    setTool('text');
                    break;
                case 'Delete':
                case 'Backspace':
                    deleteAllLabelsInImage();
                    break;
            }
        }
        
        // Save state for undo/redo
        function saveState() {
            const state = {
                annotations: JSON.parse(JSON.stringify(annotations)),
                allAnnotations: JSON.parse(JSON.stringify(allAnnotations)),
                currentImageIndex: currentImageIndex
            };
            
            undoStack.push(state);
            if (undoStack.length > MAX_UNDO_STEPS) {
                undoStack.shift();
            }
            
            // Clear redo stack when new action is performed
            redoStack = [];
            updateUndoRedoButtons();
        }
        
        // Undo function
        function undo() {
            if (undoStack.length === 0) return;
            
            // Save current state to redo stack
            const currentState = {
                annotations: JSON.parse(JSON.stringify(annotations)),
                allAnnotations: JSON.parse(JSON.stringify(allAnnotations)),
                currentImageIndex: currentImageIndex
            };
            redoStack.push(currentState);
            
            // Restore previous state
            const previousState = undoStack.pop();
            annotations = previousState.annotations;
            allAnnotations = previousState.allAnnotations;
            
            redrawCanvas();
            updateStatus('Undo performed');
            updateUndoRedoButtons();
        }
        
        // Redo function
        function redo() {
            if (redoStack.length === 0) return;
            
            // Save current state to undo stack
            const currentState = {
                annotations: JSON.parse(JSON.stringify(annotations)),
                allAnnotations: JSON.parse(JSON.stringify(allAnnotations)),
                currentImageIndex: currentImageIndex
            };
            undoStack.push(currentState);
            
            // Restore next state
            const nextState = redoStack.pop();
            annotations = nextState.annotations;
            allAnnotations = nextState.allAnnotations;
            
            redrawCanvas();
            updateStatus('Redo performed');
            updateUndoRedoButtons();
        }
        
        // Update undo/redo button states
        function updateUndoRedoButtons() {
            undoBtn.disabled = undoStack.length === 0;
            redoBtn.disabled = redoStack.length === 0;
            
            undoBtn.style.opacity = undoStack.length === 0 ? '0.5' : '1';
            redoBtn.style.opacity = redoStack.length === 0 ? '0.5' : '1';
        }
        
        // Delete all labels in current image
        function deleteAllLabelsInImage() {
            if (annotations.length === 0) {
                updateStatus('No labels to delete in current image');
                return;
            }
            
            if (confirm(`Are you sure you want to delete all ${annotations.length} labels in this image?`)) {
                saveState();
                annotations = [];
                selectedAnnotation = null;
                redrawCanvas();
                updateStatus(`Deleted all labels in current image`);
                closeMobileMenu();
            }
        }
        
        // Set the current tool
        function setTool(tool) {
            // Cancel any ongoing polygon creation when switching tools
            if (isPolygonMode) {
                isPolygonMode = false;
                tempPoints = [];
                currentShape = null;
                redrawCanvas();
            }
            
            currentTool = tool;
            
            // Update button states
            selectBtn.classList.remove('active');
            rectBtn.classList.remove('active');
            polygonBtn.classList.remove('active');
            textBtn.classList.remove('active');
            
            switch (tool) {
                case 'select':
                    selectBtn.classList.add('active');
                    break;
                case 'rect':
                    rectBtn.classList.add('active');
                    break;
                case 'polygon':
                    polygonBtn.classList.add('active');
                    break;
                case 'text':
                    textBtn.classList.add('active');
                    break;
            }
            
            canvas.style.cursor = tool === 'select' ? 'default' : 'crosshair';
            updateStatus(`Tool: ${tool.charAt(0).toUpperCase() + tool.slice(1)} selected`);
            closeMobileMenu();
        }
        
        // Open file handler
        function openFile() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.multiple = true;
            
            input.onchange = (e) => {
                const files = Array.from(e.target.files);
                if (files.length > 0) {
                    images = files;
                    currentImageIndex = 0;
                    loadImage(images[currentImageIndex]);
                    updateFileList();
                    closeMobileMenu();
                }
            };
            
            input.click();
        }
        
        // Open directory handler (simulated)
        function openDirectory() {
            const input = document.createElement('input');
            input.type = 'file';
            input.webkitdirectory = true;
            input.multiple = true;
            input.accept = 'image/*';
            
            input.onchange = (e) => {
                const files = Array.from(e.target.files);
                if (files.length > 0) {
                    images = files;
                    currentImageIndex = 0;
                    loadImage(images[currentImageIndex]);
                    updateFileList();
                    updateStatus(`Opened directory with ${files.length} images`);
                    closeMobileMenu();
                }
            };
            
            input.click();
        }
        
        // Load an image onto the canvas
        function loadImage(file) {
            const reader = new FileReader();
            
            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    currentImage = img;
                    
                    // Set canvas dimensions to match image with proper scaling for mobile
                    const containerRect = canvas.parentElement.getBoundingClientRect();
                    const maxWidth = isMobile ? containerRect.width - 20 : containerRect.width;
                    const maxHeight = isMobile ? containerRect.height - 20 : containerRect.height;
                    
                    let canvasWidth = img.width;
                    let canvasHeight = img.height;
                    
                    // Scale down if needed
                    const scaleX = maxWidth / img.width;
                    const scaleY = maxHeight / img.height;
                    const scale = Math.min(scaleX, scaleY, 1);
                    
                    if (scale < 1) {
                        canvasWidth = img.width * scale;
                        canvasHeight = img.height * scale;
                    }
                    
                    canvas.width = img.width;
                    canvas.height = img.height;
                    canvas.style.width = canvasWidth + 'px';
                    canvas.style.height = canvasHeight + 'px';
                    
                    // Draw the image
                    redrawCanvas();
                    
                    // Load annotations for this image
                    loadAnnotationsForImage(file.name);
                    
                    updateStatus(`Loaded: ${file.name} (${img.width}x${img.height})`);
                };
                img.src = e.target.result;
            };
            
            reader.readAsDataURL(file);
        }
        
        // Save current annotations before switching images
        function saveCurrentAnnotations() {
            if (currentImageIndex >= 0 && images[currentImageIndex]) {
                const currentImageName = images[currentImageIndex].name;
                allAnnotations[currentImageName] = annotations;
            }
        }
        
        // Load annotations for the current image
        function loadAnnotationsForImage(imageName) {
            annotations = allAnnotations[imageName] || [];
            selectedAnnotation = null;
            redrawCanvas();
        }
        
        // Update the file list in the sidebar
        function updateFileList() {
            fileList.innerHTML = '';
            
            images.forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = `file-item ${index === currentImageIndex ? 'active' : ''}`;
                fileItem.textContent = file.name;
                fileItem.addEventListener('click', () => {
                    saveCurrentAnnotations();
                    currentImageIndex = index;
                    loadImage(images[currentImageIndex]);
                    updateFileList();
                    closeMobileMenu();
                });
                fileList.appendChild(fileItem);
            });
        }
        
        // Add a new label
        function addLabel(name, color, category = 'objects') {
            const label = {
                id: currentLabelId++,
                name: name,
                color: color,
                category: category
            };
            
            labels.push(label);
            updateLabelList();
            return label;
        }
        
        // Update the label list in the sidebar
        function updateLabelList() {
            // Clear existing labels but keep category headers
            const categories = document.querySelectorAll('.label-category');
            labelList.innerHTML = '';
            
            // Re-add category headers
            const categoriesData = [
                { name: 'Animals', key: 'animals' },
                { name: 'People', key: 'people' },
                { name: 'Objects', key: 'objects' }
            ];
            
            categoriesData.forEach(cat => {
                const categoryElement = document.createElement('div');
                categoryElement.className = 'label-category';
                categoryElement.textContent = cat.name;
                labelList.appendChild(categoryElement);
                
                // Add labels for this category
                labels.filter(label => label.category === cat.key).forEach(label => {
                    const labelItem = document.createElement('div');
                    labelItem.className = `label-item ${selectedLabel && selectedLabel.id === label.id ? 'active' : ''}`;
                    labelItem.dataset.labelId = label.id;
                    
                    const colorBox = document.createElement('div');
                    colorBox.className = 'label-color';
                    colorBox.style.backgroundColor = label.color;
                    
                    const nameSpan = document.createElement('span');
                    nameSpan.className = 'label-name';
                    nameSpan.textContent = label.name;
                    
                    labelItem.appendChild(colorBox);
                    labelItem.appendChild(nameSpan);
                    
                    labelItem.addEventListener('click', () => {
                        selectedLabel = label;
                        updateLabelList();
                        updateStatus(`Selected label: ${label.name}`);
                        closeMobileMenu();
                    });
                    
                    labelList.appendChild(labelItem);
                });
            });
        }
        
        // Show the add label modal
        function showAddLabelModal() {
            labelNameInput.value = '';
            labelColorInput.value = '#FF0000';
            labelCategoryInput.value = 'objects';
            labelModal.style.display = 'flex';
            closeMobileMenu();
        }
        
        // Hide the add label modal
        function hideAddLabelModal() {
            labelModal.style.display = 'none';
        }
        
        // Show the select label modal
        function showSelectLabelModal() {
            selectLabelList.innerHTML = '';
            
            labels.forEach(label => {
                const labelItem = document.createElement('div');
                labelItem.className = 'label-item';
                
                const colorBox = document.createElement('div');
                colorBox.className = 'label-color';
                colorBox.style.backgroundColor = label.color;
                
                const nameSpan = document.createElement('span');
                nameSpan.className = 'label-name';
                nameSpan.textContent = label.name;
                
                labelItem.appendChild(colorBox);
                labelItem.appendChild(nameSpan);
                
                labelItem.addEventListener('click', () => {
                    selectedLabel = label;
                    hideSelectLabelModal();
                    updateLabelList();
                    updateStatus(`Selected label: ${label.name}`);
                });
                
                selectLabelList.appendChild(labelItem);
            });
            
            selectLabelModal.style.display = 'flex';
            closeMobileMenu();
        }
        
        // Hide the select label modal
        function hideSelectLabelModal() {
            selectLabelModal.style.display = 'none';
        }
        
        // Save a new label
        function saveLabel() {
            const name = labelNameInput.value.trim();
            const color = labelColorInput.value;
            const category = labelCategoryInput.value;
            
            if (name) {
                addLabel(name, color, category);
                hideAddLabelModal();
                updateStatus(`Added new label: ${name}`);
            }
        }
        
        // Show the export modal
        function showExportModal() {
            exportModal.style.display = 'flex';
            closeMobileMenu();
        }
        
        // Hide the export modal
        function hideExportModal() {
            exportModal.style.display = 'none';
        }
        
        // Export labels in the selected format
        function exportLabels() {
            const format = exportFormatSelect.value;
            let exportData;
            
            // Save current annotations before export
            saveCurrentAnnotations();
            
            switch (format) {
                case 'json':
                    exportData = JSON.stringify({
                        images: images.map(img => img.name),
                        labels: labels,
                        annotations: allAnnotations
                    }, null, 2);
                    break;
                case 'xml':
                    exportData = generatePascalVOC();
                    break;
                case 'yolo':
                    exportData = generateYOLO();
                    break;
                case 'csv':
                    exportData = generateCSV();
                    break;
                default:
                    exportData = JSON.stringify(allAnnotations, null, 2);
            }
            
            // Create a download link
            const blob = new Blob([exportData], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `labels_${new Date().toISOString().slice(0, 10)}.${format}`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            hideExportModal();
            updateStatus(`Labels exported as ${format}`);
        }
        
        // Export labeled images with annotations
        function exportLabeledImages() {
            if (!currentImage || annotations.length === 0) {
                updateStatus('No labeled images to export');
                return;
            }
            
            // Export current image with annotations
            const labeledCanvas = document.createElement('canvas');
            labeledCanvas.width = canvas.width;
            labeledCanvas.height = canvas.height;
            const labeledCtx = labeledCanvas.getContext('2d');
            
            // Draw the original image
            labeledCtx.drawImage(currentImage, 0, 0);
            
            // Draw annotations
            annotations.forEach(ann => {
                const label = labels.find(l => l.id === ann.labelId);
                if (!label) return;
                
                labeledCtx.strokeStyle = label.color;
                labeledCtx.fillStyle = label.color + '40';
                labeledCtx.lineWidth = 2;
                
                if (ann.shape === 'rect') {
                    const x = Math.min(ann.points[0].x, ann.points[1].x);
                    const y = Math.min(ann.points[0].y, ann.points[1].y);
                    const width = Math.abs(ann.points[1].x - ann.points[0].x);
                    const height = Math.abs(ann.points[1].y - ann.points[0].y);
                    
                    labeledCtx.fillRect(x, y, width, height);
                    labeledCtx.strokeRect(x, y, width, height);
                    
                    // Draw label name
                    labeledCtx.fillStyle = label.color;
                    labeledCtx.font = '12px Arial';
                    labeledCtx.fillText(label.name, x + 5, y + 15);
                } else if (ann.shape === 'polygon') {
                    labeledCtx.beginPath();
                    labeledCtx.moveTo(ann.points[0].x, ann.points[0].y);
                    
                    for (let i = 1; i < ann.points.length; i++) {
                        labeledCtx.lineTo(ann.points[i].x, ann.points[i].y);
                    }
                    
                    labeledCtx.closePath();
                    labeledCtx.fill();
                    labeledCtx.stroke();
                    
                    // Draw label name
                    labeledCtx.fillStyle = label.color;
                    labeledCtx.font = '12px Arial';
                    const center = getPolygonCenter(ann.points);
                    labeledCtx.fillText(label.name, center.x, center.y);
                }
            });
            
            // Create download link
            const imageName = images[currentImageIndex].name.replace(/\.[^/.]+$/, '') + '_labeled.png';
            labeledCanvas.toBlob(blob => {
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = imageName;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            });
            
            updateStatus(`Exported labeled image: ${imageName}`);
        }
        
        // Generate Pascal VOC XML format
        function generatePascalVOC() {
            let xml = '<?xml version="1.0"?>\n<annotation>\n';
            
            if (currentImage && annotations.length > 0) {
                const imageName = images[currentImageIndex].name;
                xml += `  <filename>${imageName}</filename>\n`;
                xml += `  <size>\n`;
                xml += `    <width>${currentImage.width}</width>\n`;
                xml += `    <height>${currentImage.height}</height>\n`;
                xml += `    <depth>3</depth>\n`;
                xml += `  </size>\n`;
                
                annotations.forEach(ann => {
                    const label = labels.find(l => l.id === ann.labelId);
                    if (label && ann.shape === 'rect') {
                        xml += `  <object>\n`;
                        xml += `    <name>${label.name}</name>\n`;
                        xml += `    <bndbox>\n`;
                        xml += `      <xmin>${Math.min(ann.points[0].x, ann.points[1].x)}</xmin>\n`;
                        xml += `      <ymin>${Math.min(ann.points[0].y, ann.points[1].y)}</ymin>\n`;
                        xml += `      <xmax>${Math.max(ann.points[0].x, ann.points[1].x)}</xmax>\n`;
                        xml += `      <ymax>${Math.max(ann.points[0].y, ann.points[1].y)}</ymax>\n`;
                        xml += `    </bndbox>\n`;
                        xml += `  </object>\n`;
                    } else if (label && ann.shape === 'polygon') {
                        xml += `  <object>\n`;
                        xml += `    <name>${label.name}</name>\n`;
                        xml += `    <polygon>\n`;
                        ann.points.forEach((point, index) => {
                            xml += `      <point${index + 1}>\n`;
                            xml += `        <x>${point.x}</x>\n`;
                            xml += `        <y>${point.y}</y>\n`;
                            xml += `      </point${index + 1}>\n`;
                        });
                        xml += `    </polygon>\n`;
                        xml += `  </object>\n`;
                    }
                });
            }
            
            xml += '</annotation>';
            return xml;
        }
        
        // Generate YOLO format
        function generateYOLO() {
            let yolo = '';
            
            if (currentImage && annotations.length > 0) {
                annotations.forEach(ann => {
                    const label = labels.find(l => l.id === ann.labelId);
                    if (label && ann.shape === 'rect') {
                        const labelIndex = labels.findIndex(l => l.id === ann.labelId);
                        const x = (ann.points[0].x + ann.points[1].x) / 2 / currentImage.width;
                        const y = (ann.points[0].y + ann.points[1].y) / 2 / currentImage.height;
                        const width = Math.abs(ann.points[1].x - ann.points[0].x) / currentImage.width;
                        const height = Math.abs(ann.points[1].y - ann.points[0].y) / currentImage.height;
                        
                        yolo += `${labelIndex} ${x.toFixed(6)} ${y.toFixed(6)} ${width.toFixed(6)} ${height.toFixed(6)}\n`;
                    }
                });
            }
            
            return yolo.trim();
        }
        
        // Generate CSV format
        function generateCSV() {
            let csv = 'image_name,label,shape,x1,y1,x2,y2,width,height,points\n';
            
            if (currentImage && annotations.length > 0) {
                const imageName = images[currentImageIndex].name;
                const imgWidth = currentImage.width;
                const imgHeight = currentImage.height;
                
                annotations.forEach(ann => {
                    const label = labels.find(l => l.id === ann.labelId);
                    if (label) {
                        if (ann.shape === 'rect') {
                            const x1 = Math.min(ann.points[0].x, ann.points[1].x);
                            const y1 = Math.min(ann.points[0].y, ann.points[1].y);
                            const x2 = Math.max(ann.points[0].x, ann.points[1].x);
                            const y2 = Math.max(ann.points[0].y, ann.points[1].y);
                            
                            csv += `${imageName},${label.name},rect,${x1},${y1},${x2},${y2},${imgWidth},${imgHeight},\n`;
                        } else if (ann.shape === 'polygon') {
                            const pointsStr = ann.points.map(p => `${p.x},${p.y}`).join(';');
                            csv += `${imageName},${label.name},polygon,,,,,${imgWidth},${imgHeight},"${pointsStr}"\n`;
                        }
                    }
                });
            }
            
            return csv.trim();
        }
        
        // Save the current project
        function saveProject() {
            // Save current annotations before saving project
            saveCurrentAnnotations();
            
            const projectData = {
                images: images.map(img => img.name),
                labels: labels,
                allAnnotations: allAnnotations,
                currentImageIndex: currentImageIndex,
                currentLabelId: currentLabelId
            };
            
            const blob = new Blob([JSON.stringify(projectData, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'labeling_project.json';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            updateStatus('Project saved');
        }
        
        // Redraw the canvas with improved polygon visualization
        function redrawCanvas() {
            if (!currentImage) return;
            
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(currentImage, 0, 0);
            
            // Draw existing annotations
            annotations.forEach(ann => {
                const label = labels.find(l => l.id === ann.labelId);
                if (!label) return;
                
                ctx.strokeStyle = label.color;
                ctx.fillStyle = label.color + '40'; // Add transparency
                ctx.lineWidth = 2;
                
                if (ann.shape === 'rect') {
                    const x = Math.min(ann.points[0].x, ann.points[1].x);
                    const y = Math.min(ann.points[0].y, ann.points[1].y);
                    const width = Math.abs(ann.points[1].x - ann.points[0].x);
                    const height = Math.abs(ann.points[1].y - ann.points[0].y);
                    
                    ctx.fillRect(x, y, width, height);
                    ctx.strokeRect(x, y, width, height);
                    
                    // Draw label name
                    ctx.fillStyle = label.color;
                    ctx.font = '12px Arial';
                    ctx.fillText(label.name, x + 5, y + 15);
                } else if (ann.shape === 'polygon') {
                    ctx.beginPath();
                    ctx.moveTo(ann.points[0].x, ann.points[0].y);
                    
                    for (let i = 1; i < ann.points.length; i++) {
                        ctx.lineTo(ann.points[i].x, ann.points[i].y);
                    }
                    
                    ctx.closePath();
                    ctx.fill();
                    ctx.stroke();
                    
                    // Draw vertices
                    ctx.fillStyle = label.color;
                    ann.points.forEach(point => {
                        ctx.beginPath();
                        ctx.arc(point.x, point.y, 3, 0, 2 * Math.PI);
                        ctx.fill();
                    });
                    
                    // Draw label name
                    ctx.font = '12px Arial';
                    const center = getPolygonCenter(ann.points);
                    ctx.fillText(label.name, center.x, center.y);
                } else if (ann.shape === 'text') {
                    ctx.fillStyle = label.color;
                    ctx.font = '16px Arial';
                    ctx.fillText(ann.text, ann.points[0].x, ann.points[0].y);
                }
                
                // Highlight if selected
                if (selectedAnnotation && selectedAnnotation.id === ann.id) {
                    ctx.strokeStyle = '#FFFF00';
                    ctx.lineWidth = 3;
                    
                    if (ann.shape === 'rect') {
                        const x = Math.min(ann.points[0].x, ann.points[1].x);
                        const y = Math.min(ann.points[0].y, ann.points[1].y);
                        const width = Math.abs(ann.points[1].x - ann.points[0].x);
                        const height = Math.abs(ann.points[1].y - ann.points[0].y);
                        
                        ctx.strokeRect(x, y, width, height);
                    } else if (ann.shape === 'polygon') {
                        ctx.beginPath();
                        ctx.moveTo(ann.points[0].x, ann.points[0].y);
                        
                        for (let i = 1; i < ann.points.length; i++) {
                            ctx.lineTo(ann.points[i].x, ann.points[i].y);
                        }
                        
                        ctx.closePath();
                        ctx.stroke();
                    }
                }
            });
            
            // Draw current shape being drawn
            if (tempPoints.length > 0 && selectedLabel) {
                ctx.strokeStyle = selectedLabel.color;
                ctx.fillStyle = selectedLabel.color + '40';
                ctx.lineWidth = 2;
                
                if (currentShape === 'rect' && tempPoints.length === 2) {
                    ctx.setLineDash([5, 5]);
                    const x = Math.min(tempPoints[0].x, tempPoints[1].x);
                    const y = Math.min(tempPoints[0].y, tempPoints[1].y);
                    const width = Math.abs(tempPoints[1].x - tempPoints[0].x);
                    const height = Math.abs(tempPoints[1].y - tempPoints[0].y);
                    
                    ctx.fillRect(x, y, width, height);
                    ctx.strokeRect(x, y, width, height);
                    ctx.setLineDash([]);
                } else if (isPolygonMode) {
                    // Draw polygon in progress
                    if (tempPoints.length >= 1) {
                        // Fill if we have at least 3 points
                        if (tempPoints.length >= 3) {
                            ctx.setLineDash([]);
                            ctx.beginPath();
                            ctx.moveTo(tempPoints[0].x, tempPoints[0].y);
                            
                            for (let i = 1; i < tempPoints.length; i++) {
                                ctx.lineTo(tempPoints[i].x, tempPoints[i].y);
                            }
                            
                            ctx.closePath();
                            ctx.fill();
                        }
                        
                        // Draw the outline
                        ctx.setLineDash([5, 5]);
                        ctx.beginPath();
                        ctx.moveTo(tempPoints[0].x, tempPoints[0].y);
                        
                        for (let i = 1; i < tempPoints.length; i++) {
                            ctx.lineTo(tempPoints[i].x, tempPoints[i].y);
                        }
                        
                        // Draw line to current mouse position
                        if (lastMouseX && lastMouseY) {
                            const rect = canvas.getBoundingClientRect();
                            const mouseX = (lastMouseX - rect.left) * (canvas.width / rect.width);
                            const mouseY = (lastMouseY - rect.top) * (canvas.height / rect.height);
                            ctx.lineTo(mouseX, mouseY);
                        }
                        
                        ctx.stroke();
                        ctx.setLineDash([]);
                        
                        // Draw vertices
                        ctx.fillStyle = selectedLabel.color;
                        tempPoints.forEach(point => {
                            ctx.beginPath();
                            ctx.arc(point.x, point.y, 4, 0, 2 * Math.PI);
                            ctx.fill();
                        });
                    }
                }
            }
        }
        
        // Get annotation at a specific point
        function getAnnotationAtPoint(x, y) {
            // Check in reverse order to select top-most annotation
            for (let i = annotations.length - 1; i >= 0; i--) {
                const ann = annotations[i];
                
                if (ann.shape === 'rect') {
                    const minX = Math.min(ann.points[0].x, ann.points[1].x);
                    const minY = Math.min(ann.points[0].y, ann.points[1].y);
                    const maxX = Math.max(ann.points[0].x, ann.points[1].x);
                    const maxY = Math.max(ann.points[0].y, ann.points[1].y);
                    
                    if (x >= minX && x <= maxX && y >= minY && y <= maxY) {
                        return ann;
                    }
                } else if (ann.shape === 'polygon') {
                    if (isPointInPolygon(ann.points, {x, y})) {
                        return ann;
                    }
                } else if (ann.shape === 'text') {
                    // Simple approximation for text selection
                    const textX = ann.points[0].x;
                    const textY = ann.points[0].y;
                    
                    if (Math.abs(x - textX) < 50 && Math.abs(y - textY) < 20) {
                        return ann;
                    }
                }
            }
            
            return null;
        }
        
        // Check if a point is inside a polygon using ray casting algorithm
        function isPointInPolygon(polygon, point) {
            let inside = false;
            for (let i = 0, j = polygon.length - 1; i < polygon.length; j = i++) {
                const xi = polygon[i].x, yi = polygon[i].y;
                const xj = polygon[j].x, yj = polygon[j].y;
                
                const intersect = ((yi > point.y) !== (yj > point.y))
                    && (point.x < (xj - xi) * (point.y - yi) / (yj - yi) + xi);
                if (intersect) inside = !inside;
            }
            
            return inside;
        }
        
        // Get the center of a polygon
        function getPolygonCenter(points) {
            let x = 0, y = 0;
            for (const point of points) {
                x += point.x;
                y += point.y;
            }
            return {
                x: x / points.length,
                y: y / points.length
            };
        }
        
        // Navigate to previous image
        function prevImage() {
            if (images.length === 0) return;
            
            // Cancel any ongoing polygon creation
            if (isPolygonMode) {
                isPolygonMode = false;
                tempPoints = [];
                currentShape = null;
            }
            
            saveCurrentAnnotations();
            currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
            loadImage(images[currentImageIndex]);
            updateFileList();
        }
        
        // Navigate to next image
        function nextImage() {
            if (images.length === 0) return;
            
            // Cancel any ongoing polygon creation
            if (isPolygonMode) {
                isPolygonMode = false;
                tempPoints = [];
                currentShape = null;
            }
            
            saveCurrentAnnotations();
            currentImageIndex = (currentImageIndex + 1) % images.length;
            loadImage(images[currentImageIndex]);
            updateFileList();
        }
        
        // Update status bar
        function updateStatus(message) {
            statusBar.textContent = message;
        }
        
        // Initialize the application
        window.addEventListener('load', init);
    </script>
</body>
</html>