<x-app-layout>
  <style>
    body {
      font-family: 'Inter', system-ui, sans-serif;
      background: linear-gradient(to bottom right, #f9fafb, #eef2ff);
      color: #111827;
      transition: background 0.3s, color 0.3s;
    }
    @media (prefers-color-scheme: dark) {
      body {
        background: linear-gradient(to bottom right, #0f172a, #1e1b4b);
        color: #f1f5f9;
      }
    }

    .template-card {
      transition: all 0.35s ease;
      cursor: pointer;
      background: rgba(255, 255, 255, 0.75);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255, 255, 255, 0.4);
    }
    .template-card:hover {
      transform: translateY(-6px) scale(1.02);
      box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    }

    .filter-btn {
      transition: all 0.3s ease;
      cursor: pointer;
      background: white;
      border: 2px solid #e5e7eb;
      padding: 0.75rem 1.5rem;
      border-radius: 9999px;
      font-weight: 600;
      color: #6b7280;
    }
    
    .filter-btn:hover {
      border-color: #6366f1;
      color: #6366f1;
      transform: translateY(-2px);
    }
    
    .filter-btn.active {
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      color: white;
      border-color: #6366f1;
      box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
    }

    .modal-bg {
      background: rgba(0, 0, 0, 0.85);
      position: fixed;
      inset: 0;
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 100;
      animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    .modal-content {
      background: #fff;
      width: 96%;
      height: 90%;
      max-width: 1400px;
      border-radius: 14px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      animation: scaleIn 0.3s ease;
    }
    @keyframes scaleIn {
      from { transform: scale(0.95); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }
    iframe {
      flex-grow: 1;
      width: 100%;
      border: none;
    }

    .badge {
      display: inline-block;
      padding: 0.25rem 0.75rem;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .badge-geral { background: #dbeafe; color: #1e40af; }
    .badge-advocacia { background: #ddd6fe; color: #5b21b6; }
    .badge-eventos { background: #fbcfe8; color: #be185d; }
    .badge-medico { background: #d1fae5; color: #065f46; }

    .stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 1rem;
      margin: 2rem 0;
    }

    .stat-card {
      background: white;
      padding: 1.5rem;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .stat-number {
      font-size: 2.5rem;
      font-weight: 800;
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .stat-label {
      font-size: 0.875rem;
      color: #6b7280;
      margin-top: 0.5rem;
    }
  </style>

<div class="min-h-screen flex flex-col">

  <!-- HEADER 
  <header class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white py-12 shadow-lg">
    <div class="max-w-7xl mx-auto text-center px-4">
      <h1 class="text-4xl md:text-6xl font-extrabold mb-3 tracking-tight">✨ Galeria de Templates</h1>
      <p class="text-indigo-100 text-xl mb-6">20+ templates profissionais — modernos, dinâmicos e responsivos</p>-->
      
      <!-- Stats 
      <div class="stats max-w-4xl mx-auto">
        <div class="stat-card">
          <div class="stat-number">20+</div>
          <div class="stat-label">Templates</div>
        </div>
        <div class="stat-card">
          <div class="stat-number">4+</div>
          <div class="stat-label">Nichos</div>
        </div>
        <div class="stat-card">
          <div class="stat-number">100%</div>
          <div class="stat-label">Responsivos</div>
        </div>
        <div class="stat-card">
          <div class="stat-number">JSON</div>
          <div class="stat-label">Customizável</div>
        </div>
      </div>
    </div>
  </header> -->

  <!-- FILTERS -->
  <div class="bg-white/80 backdrop-blur-md shadow-md sticky top-0 z-50 py-4">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex flex-wrap gap-3 justify-center items-center">
        <span class="text-sm font-semibold text-gray-600 mr-2">Filtrar por:</span>
        <button class="filter-btn active" data-filter="todos">
          🌟 Todos (20)
        </button>
        <button class="filter-btn" data-filter="geral">
          🏢 Geral (12)
        </button>
        <button class="filter-btn" data-filter="advocacia">
          ⚖️ Advocacia (2)
        </button>
        <button class="filter-btn" data-filter="eventos">
          🎉 Eventos (2)
        </button>
        <button class="filter-btn" data-filter="medico">
          🏥 Médico (2)
        </button>
        <button class="filter-btn" data-filter="restaurante">
          🍽️ Restaurante (2)
        </button>
      </div>
    </div>
  </div>

  <!-- MAIN -->
  <main class="flex-grow py-12">
    <div class="max-w-7xl mx-auto px-6">
      <!-- Contador de resultados -->
      <div class="mb-8 text-center">
        <p class="text-lg text-gray-600" id="result-count">
          Exibindo <span class="font-bold text-indigo-600">20</span> templates
        </p>
      </div>

      <!-- Grid de templates -->
      <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3" id="templates-grid">
        <!-- Cards gerados via JS -->
      </div>

      <!-- Mensagem quando não há resultados -->
      <div id="no-results" class="hidden text-center py-20">
        <div class="text-6xl mb-4">🔍</div>
        <h3 class="text-2xl font-bold text-gray-700 mb-2">Nenhum template encontrado</h3>
        <p class="text-gray-500">Tente outro filtro</p>
      </div>
    </div>
  </main>

  <!-- FOOTER -->


  <!-- MODAL PREVIEW -->
  <div id="modal" class="modal-bg">
    <div class="modal-content">
      <div class="flex justify-between items-center bg-indigo-600/90 text-white px-6 py-4 backdrop-blur">
        <div>
          <h3 id="modal-titulo" class="font-semibold text-xl"></h3>
          <p id="modal-nicho" class="text-sm text-indigo-200 mt-1"></p>
        </div>
        <button id="modal-fechar" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-all font-semibold">
          Fechar ✕
        </button>
      </div>
      <iframe id="iframe-demo"></iframe>
    </div>
  </div>

  <script>
    const templates = [
      // GERAL (Templates 0-10)
      { id: 0, nome: "Template Base", arquivo: "template-base", json: "template-base", nicho: "geral", descricao: "Template base institucional com carrinho de compras" },
      { id: 1, nome: "Modern Clean", arquivo: "modern-clean", json: "modern-clean", nicho: "geral", descricao: "Design limpo e moderno com WhatsApp integrado" },
      { id: 2, nome: "TechVerde Soluções", arquivo: "techverde-solucoes", json: "techverde-solucoes", nicho: "geral", descricao: "Visual moderno com glassmorphism e animações suaves" },
      { id: 3, nome: "NeoTech Digital", arquivo: "neotech-digital", json: "dneotech-digital", nicho: "geral", descricao: "Tema dark futurista com efeitos neon" },
      { id: 4, nome: "ProBusiness Solutions", arquivo: "probusiness-solutions", json: "probusiness-solutions", nicho: "geral", descricao: "Corporativo elegante com parallax" },
      { id: 5, nome: "Magazine Style", arquivo: "magazine-style", json: "magazine-style", nicho: "geral", descricao: "Estilo editorial com grid assimétrico" },
      { id: 6, nome: "StartupFlow", arquivo: "startupflow", json: "startupflow", nicho: "geral", descricao: "Moderno com blobs animados e gradientes" },
      { id: 7, nome: "BoldWorks Studio", arquivo: "boldworks-studio", json: "boldworks-studio", nicho: "geral", descricao: "Estilo brutalista com bordas marcantes" },
      { id: 8, nome: "Aurum Agency", arquivo: "aurum-agency", json: "aurum-agency", nicho: "geral", descricao: "Luxo e sofisticação com dourado" },
      { id: 9, nome: "Editorial Essence", arquivo: "editorial-essence", json: "editorial-essence", nicho: "geral", descricao: "Revista digital editorial" },
      { id: 10, nome: "Showcase Studio", arquivo: "showcase-studio", json: "showcase-studio", nicho: "geral", descricao: "Portfolio moderno com grid criativo" },

      // ADVOCACIA (2 templates)
      { id: 11, nome: "Silva & Advogados", arquivo: "silva-advogados", json: "silva-advogados", nicho: "advocacia", descricao: "Escritório tradicional com áreas de atuação" },
      { id: 12, nome: "Direito Digital", arquivo: "direito-digital", json: "direito-digital", nicho: "advocacia", descricao: "Advocacia moderna 100% online" },

      // EVENTOS (2 templates)
      { id: 13, nome: "Festas Perfeitas", arquivo: "festas-perfeitas", json: "festas-perfeitas", nicho: "eventos", descricao: "Festas e celebrações completas" },
      { id: 14, nome: "Prime Events", arquivo: "prime-events", json: "prime-events", nicho: "eventos", descricao: "Eventos corporativos de alto padrão" },

      // MÉDICO (2 templates)
      { id: 15, nome: "Clínica Vida & Saúde", arquivo: "clinica-vida-saude", json: "clinica-vida-saude", nicho: "medico", descricao: "Clínica multiespecializada completa" },
      { id: 16, nome: "Espaço Saúde Integral", arquivo: "espaco-saude-integral", json: "espaco-saude-integral", nicho: "medico", descricao: "Centro médico com telemedicina" },

      //Restaurante
            { 
        id: 17,
        nome: "Sabor & Arte Restaurante",
        arquivo: "sabor-arte-restaurante",
        json: "sabor-arte-restaurante",
        nicho: "restaurante",
        descricao: "Restaurante elegante com cardápio dinâmico e reservas online"
      },
      { 
        id: 18,
        nome: "Bistrô Gourmet",
        arquivo: "bistro-gourmet",
        json: "bistro-gourmet",
        nicho: "restaurante",
        descricao: "Bistrô moderno com sistema de pedidos e eventos especiais"
      },  
      //site institucional multipaginas
      { id: 20, nome: "Institucional MultiPáginas", arquivo: "institucional-multipaginas", json: "institucional-multipaginas", nicho: "geral", descricao: "Site institucional com múltiplas páginas e navegação aprimorada" },
    ];

    const grid = document.getElementById("templates-grid");
    const noResults = document.getElementById("no-results");
    const resultCount = document.getElementById("result-count");
    let currentFilter = "todos";

    // Função para obter badge do nicho
    function getBadge(nicho) {
      const badges = {
        geral: '<span class="badge badge-geral">🏢 Geral</span>',
        advocacia: '<span class="badge badge-advocacia">⚖️ Advocacia</span>',
        eventos: '<span class="badge badge-eventos">🎉 Eventos</span>',
        medico: '<span class="badge badge-medico">🏥 Médico</span>',
        restaurante: '<span class="badge badge-restaurante">🍽️ Restaurante</span>',
      };
      return badges[nicho] || '';
    }

    // Dados de fallback para cada template (caso JSON não carregue)
    const templateData = {
      0: { banner: "https://placehold.co/600x300?text=Template+Base", descricao: "Template base institucional com carrinho de compras" },
      1: { banner: "https://placehold.co/600x300?text=Modern+Clean", descricao: "Design limpo e moderno com WhatsApp integrado" },
      2: { banner: "https://placehold.co/600x300?text=TechVerde", descricao: "Visual moderno com glassmorphism e animações suaves" },
      3: { banner: "https://placehold.co/600x300?text=NeoTech", descricao: "Tema dark futurista com efeitos neon" },
      4: { banner: "https://placehold.co/600x300?text=ProBusiness", descricao: "Corporativo elegante com parallax" },
      5: { banner: "https://placehold.co/600x300?text=Magazine", descricao: "Estilo editorial com grid assimétrico" },
      6: { banner: "https://placehold.co/600x300?text=StartupFlow", descricao: "Moderno com blobs animados e gradientes" },
      7: { banner: "https://placehold.co/600x300?text=BoldWorks", descricao: "Estilo brutalista com bordas marcantes" },
      8: { banner: "https://placehold.co/600x300?text=Aurum", descricao: "Luxo e sofisticação com dourado" },
      9: { banner: "https://placehold.co/600x300?text=Editorial", descricao: "Revista digital editorial" },
      10: { banner: "https://placehold.co/600x300?text=Showcase", descricao: "Portfolio moderno com grid criativo" },
      11: { banner: "https://placehold.co/600x400?text=Justiça+Advocacia", descricao: "Escritório tradicional com áreas de atuação detalhadas" },
      12: { banner: "https://placehold.co/600x400?text=Tech+Futurista", descricao: "Advocacia moderna 100% online e ágil" },
      13: { banner: "https://placehold.co/600x400?text=Eventos+Festas", descricao: "Planejamento completo de festas e celebrações" },
      14: { banner: "https://placehold.co/600x400?text=Corporativo", descricao: "Eventos corporativos e convenções" },
      15: { banner: "https://placehold.co/600x400?text=Clinica+Saude", descricao: "Clínica multiespecializada completa" },
      16: { banner: "https://placehold.co/600x400?text=Startup+Hero", descricao: "Centro médico com telemedicina integrada" },
      17: {
        banner: "https://placehold.co/600x400?text=Sabor+%26+Arte+Restaurante",
        descricao: "Restaurante elegante com cardápio dinâmico e reservas online"
      },
      18: {
        banner: "https://placehold.co/600x400?text=Bistro+Gourmet",
        descricao: "Bistrô moderno com sistema de pedidos e eventos especiais"
      },  
      19: { banner: "https://placehold.co/600x400?text=Justiça+Advocacia", descricao: "Escritório tradicional com áreas de atuação detalhadas" }, 
      20: { banner: "https://placehold.co/600x400?text=Institucional+MultiPaginas", descricao: "Site institucional com múltiplas páginas e navegação aprimorada" },

    };

    // Função para renderizar templates
    async function renderTemplates(filter = "todos") {
      grid.innerHTML = '';
      
      const filtered = filter === "todos" 
        ? templates 
        : templates.filter(t => t.nicho === filter);

      // Atualizar contador
      resultCount.innerHTML = `Exibindo <span class="font-bold text-indigo-600">${filtered.length}</span> template${filtered.length !== 1 ? 's' : ''}`;

      if (filtered.length === 0) {
        grid.classList.add('hidden');
        noResults.classList.remove('hidden');
        return;
      }

      grid.classList.remove('hidden');
      noResults.classList.add('hidden');

      for (const tpl of filtered) {
        // Usar dados de fallback primeiro
        let banner = templateData[tpl.id]?.banner || `https://placehold.co/600x300?text=Template+${tpl.id}`;
        let descricaoJson = templateData[tpl.id]?.descricao || tpl.descricao;
        
        // Tentar carregar do JSON (só funciona se estiver em servidor)
        try {
          const resp = await fetch(tpl.json);
          if (resp.ok) {
            const data = await resp.json();
            if (data.pages?.home?.banner) banner = data.pages.home.banner;
            if (data.pages?.home?.descricao) descricaoJson = data.pages.home.descricao;
          }
        } catch (e) {
          // Silenciosamente usa fallback - normal quando abrindo arquivo local
          console.log(`📦 Usando dados locais para ${tpl.nome} (JSON não disponível em file://)`);
        }

        const card = document.createElement("div");
        card.className = "template-card rounded-xl overflow-hidden shadow-sm flex flex-col";
        card.innerHTML = `
          <div class="relative">
            <img src="${banner}" alt="${tpl.nome}" class="w-full h-48 object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent"></div>
            <div class="absolute top-3 left-3">
              ${getBadge(tpl.nicho)}
            </div>
            <div class="absolute bottom-3 left-3 text-white font-bold text-xl drop-shadow-lg">${tpl.nome}</div>
          </div>
          <div class="p-6 flex flex-col justify-between flex-grow bg-white">
            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4 line-clamp-2">${descricaoJson}</p>
            <div class="flex justify-between items-center gap-3">
              <button class="flex-1 bg-indigo-600 text-white px-4 py-3 rounded-lg hover:bg-indigo-700 transition-all font-semibold" onclick="openModal('${tpl.nome}','${tpl.arquivo}','${tpl.nicho}')">
                👁️ Ver Demo
              </button>
              <a href="/preview-template/${tpl.arquivo}" target="_blank" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium hover:underline flex items-center gap-1">
                Abrir ↗
              </a>
            </div>
          </div>
        `;
        grid.appendChild(card);
      }
    }

    // Sistema de filtros
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        // Remove active de todos
        filterButtons.forEach(b => b.classList.remove('active'));
        // Adiciona active no clicado
        btn.classList.add('active');
        
        const filter = btn.dataset.filter;
        currentFilter = filter;
        renderTemplates(filter);
      });
    });

    // Modal preview
    const modal = document.getElementById("modal");
    const iframe = document.getElementById("iframe-demo");
    const titulo = document.getElementById("modal-titulo");
    const nicho = document.getElementById("modal-nicho");

    function openModal(nome, arquivo, nichoTemplate) {
      titulo.innerText = nome;
      nicho.innerText = `Nicho: ${nichoTemplate.charAt(0).toUpperCase() + nichoTemplate.slice(1)}`;
     iframe.src = `/preview-template/${arquivo}`;
      modal.style.display = "flex";
      document.body.style.overflow = "hidden";
    }

    document.getElementById("modal-fechar").addEventListener("click", () => {
      modal.style.display = "none";
      iframe.src = "";
      document.body.style.overflow = "auto";
    });

    // Fechar modal ao clicar fora
    modal.addEventListener("click", (e) => {
      if (e.target === modal) {
        modal.style.display = "none";
        iframe.src = "";
        document.body.style.overflow = "auto";
      }
    });

    // Renderizar templates inicialmente
    renderTemplates();
  </script>
</div>
</x-app-layout>