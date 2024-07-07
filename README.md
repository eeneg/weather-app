## Gene Philip Rellanos

Weather and Map App

## Setup

- clone repo
- composer install
- npm install

## ENV Data for API KEYS (Use Responsibly) IMPORTANT

VITE_MapBoxKEY='pk.eyJ1IjoiZWVuZWciLCJhIjoiY2x5OGcwYnhwMGcyeTJycXQzMDh2aHExayJ9.nxOYSeThsuL6-jWeMbhiXQ'
VITE_OpenWeatherKEY='3ad574899038a18e87713e76c123797d'
VITE_FourSquareKEY='fsq3WP5NbCflVQMqwW0i+bZrhibZo462ZlrQdMXVknK+My8='
VITE_FourSquareCLientID='P5JHGSG5D5DOZVGGL3VNHUVDTZX5CG1FC4W3MGTWB3JMEZ1N'
VITE_FourSquareCLientSecretID='WB2XX5DA4VAJ0TOJ1YXCS4G2SHTOOK30X4KGG4YE4KFG1RHL'

Or just copy the .env.example contents

## UI

I chose to include a map in the UI because it offers several key benefits, especially given that the main users of the app are tourists. A map provides visual context, helping users better understand geographical layouts and navigate more effectively by seeing routes and landmarks. This feature makes the UI more informative when paired with weather information and details about trending venues near the searched address. The map enhances user engagement, providing an interactive and immersive experience that is particularly valuable for tourists seeking comprehensive and real-time information about their surroundings.


## Code

Readability and Maintainability:

Simplified state management.
- Clear separation of logic and template.
- Reduced Code Duplication:

Merged similar functions.
- Centralized debounce logic.
- Enhanced Performance:

Efficient reactivity with ref.
- Debounced search input reduces API calls.
- Simplified Template Structure:

Consolidated elements for clarity.
- Improved visual hierarchy.
- Better Separation of Concerns:

Modular functions for easier testing and reuse.
- Organized event handling and state management.


Overall Benefits
- Easier Maintenance: Simplified structure aids quick understanding and changes.
- Enhanced Performance: Optimized reactivity and fewer API calls improve user experience.
- Better Readability: Concise code reduces the learning curve and improves collaboration.
- Scalability: Clean separation of concerns makes it easier to add new features without affecting existing code.
