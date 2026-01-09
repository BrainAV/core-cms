# 🎨 Branding Guidelines

## 1. Visual Identity

### The Logo Concept: "The Star in the Core"
The visual representation of **Core CMS** is based on a powerful natural metaphor:
*   **The Object**: An apple core, sliced horizontally.
*   **The Reveal**: In nature, this slice reveals a perfect 5-point star shape in the center.
*   **The Energy**: The star is surrounded by a radiating sunburst, symbolizing the **Solar Plexus** (the center of power and metabolism).
*   **Assets**: Rough logo saved as `assets/images/logo.png`.

### Color Palette
*   **Deep Navy (`#1c2a38`)**: Represents the depth and stability of the backend.
*   **Solar Gold**: Represents the energy of the core.
*   **Organic Green**: Represents growth and the plugin ecosystem.

---

## 2. The Philosophy: The Human CMS

If we treat the human body as a CMS, the "Core" isn't just one organ. It’s the **Central Nervous System (CNS)**, specifically the Brain and Spinal Cord.

Here is how the architecture of a "Human CMS" maps out:

### 1. The Core: The Brain
In a CMS, the core handles the "logic"—deciding what data to fetch and how to respond to requests.
*   **The Kernel (Medulla/Brainstem)**: This is the low-level code that handles "uptime." It keeps the heartbeat and breathing going without you needing to write a script for it.
    *   **Tech**: `config/db.php` (Database Connection).
*   **The Controller (Frontal Lobe)**: This is where the business logic lives. It processes inputs (sensory data) and decides which "module" to trigger next.
    *   **Tech**: `index.php` (The Router/Front Controller).

### 2. The Database: The Hippocampus & DNA
*   **MySQL (The Hippocampus)**: This is your relational database for "Content." It stores your memories, learned facts, and experiences in a way that the Core can query and retrieve later.
    *   **Tech**: `db/schema.sql` (The Tables).
*   **The Config Files (DNA)**: This is the hard-coded configuration. It defines the "system requirements"—your height, eye color, and base biological functions. You can’t easily change these via the admin panel!
    *   **Tech**: `config/config.php` (Constants).

### 3. The API & Routing: The Spinal Cord & Nerves
In your PHP CMS, you’ll have a router that sends signals to different parts of the app.
*   **The Spinal Cord**: Acts as the main data bus (the "Backbone").
    *   **Tech**: `.htaccess` (Directs traffic).
*   **Peripheral Nerves**: The API endpoints that send signals to the "Frontend" (the muscles and skin) to perform an action or display a state.
    *   **Tech**: `$_GET` / `$_POST` requests.

### 4. The Frontend: The Skin and Face
This is the UI/UX. It’s the layer the world interacts with. It reflects the internal state of the CMS (e.g., if the "Database" is stressed, the "Frontend" might show a frown or a blush).
*   **Tech**: `templates/` and `assets/css/style.css`.

### 5. The Admin Panel: The Conscious Mind
The "Admin" is where you (the user) try to manage the system. You try to input "New Content" (learning a skill) or change "Settings" (trying to wake up earlier). Sometimes the Core ignores the Admin—like when you try to stay awake but the Core triggers a "System Shutdown" (sleep).
*   **Tech**: `/admin` directory.

### 6. Plugins: Learned Skills & Tools
Just as a human can learn to play the piano or drive a car, the CMS can extend its capabilities through **Plugins**.
*   **The Event Planner**: This is a **Specialized Skill**. It requires new memory storage (new tables in the Hippocampus) and new logic paths (new routes in the Brain). It doesn't change the fundamental DNA of the system, but it expands what the body can *do*.
    *   **Tech**: `/plugins/` directory (e.g., `/plugins/event-planner`).

### 7. AI Agents: Intuition & The Subconscious
While the Admin (Conscious Mind) makes the final decisions, AI Agents act as the **Subconscious** or **Intuition**.
*   **The Chat Agent**: Acts as **Broca’s Area (Speech Center)**. It handles communication with the outside world (visitors) automatically, without the Conscious Mind needing to micromanage every word.
*   **The Admin Copilot**: Acts as **Intuition**. It analyzes the Database (Memory) and whispers suggestions to the Admin ("You should write about this," or "Here is a summary of your events").
    *   **Tech**: Gemini API Integration.

### Summary: Decoupled Architecture
Thinking this way helps enforce separation of concerns:
*   Keep the **Core (Brain)** focused on logic.
*   Keep the **Database (Memory)** organized.
*   Keep the **Frontend (Skin)** clean and separate.
*   Keep **Plugins (Skills)** modular so they don't overwhelm the Brain.

---

## 3. AI Generation Prompts

Use the following prompt with Gemini (Imagen 3) or other image generators to create the official logo assets.

### 🍌 "Nano Banana" Prompt (Logo)
> **Prompt:**
> "A minimalist, modern tech logo design. A horizontal cross-section of an apple core, revealing the natural 5-point star shape inside. The star is glowing gold. The surrounding apple flesh is stylized and geometric, forming a subtle sunburst or solar plexus chakra symbol. Deep navy blue background. Vector art style, flat design, clean lines, organic yet structured. High quality, professional software branding."

### Icon Prompt (Favicon)
> **Prompt:**
> "A simple gold 5-point star inside a geometric circle, flat vector icon, navy blue background, app icon style."