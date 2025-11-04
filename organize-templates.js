import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const slugify = str =>
  str.toLowerCase()
     .normalize("NFD")
     .replace(/[\u0300-\u036f]/g, "")
     .replace(/[^a-z0-9]+/g, "-")
     .replace(/(^-|-$)+/g, "");

const templates = [
  { id: 0, nome: "Template Base", arquivo: "template_site_0.html", json: "dados-site-template0.json", nicho: "geral", descricao: "Template base institucional com carrinho de compras" },
  { id: 1, nome: "Modern Clean", arquivo: "template_site_1.html", json: "dados-site-template1.json", nicho: "geral", descricao: "Design limpo e moderno com WhatsApp integrado" },
  { id: 2, nome: "TechVerde Soluções", arquivo: "template_site_2.html", json: "dados-site-template2.json", nicho: "geral", descricao: "Visual moderno com glassmorphism e animações suaves" },
  { id: 3, nome: "NeoTech Digital", arquivo: "template_site_3.html", json: "dados-site-template3.json", nicho: "geral", descricao: "Tema dark futurista com efeitos neon" },
  { id: 4, nome: "ProBusiness Solutions", arquivo: "template_site_4.html", json: "dados-site-template4.json", nicho: "geral", descricao: "Corporativo elegante com parallax" },
  { id: 5, nome: "Magazine Style", arquivo: "template_site_5.html", json: "dados-site-template5.json", nicho: "geral", descricao: "Estilo editorial com grid assimétrico" },
  { id: 6, nome: "StartupFlow", arquivo: "template_site_6.html", json: "dados-site-template6.json", nicho: "geral", descricao: "Moderno com blobs animados e gradientes" },
  { id: 7, nome: "BoldWorks Studio", arquivo: "template_site_7.html", json: "dados-site-template7.json", nicho: "geral", descricao: "Estilo brutalista com bordas marcantes" },
  { id: 8, nome: "Aurum Agency", arquivo: "template_site_8.html", json: "dados-site-template8.json", nicho: "geral", descricao: "Luxo e sofisticação com dourado" },
  { id: 9, nome: "Editorial Essence", arquivo: "template_site_9.html", json: "dados-site-template9.json", nicho: "geral", descricao: "Revista digital editorial" },
  { id: 10, nome: "Showcase Studio", arquivo: "template_site_10.html", json: "dados-site-template10.json", nicho: "geral", descricao: "Portfolio moderno com grid criativo" },

  { id: 11, nome: "Silva & Advogados", arquivo: "template-advogados1.html", json: "dados-advogados1.json", nicho: "advocacia", descricao: "Escritório tradicional" },
  { id: 12, nome: "Direito Digital", arquivo: "template-advogados2.html", json: "dados-advogados2.json", nicho: "advocacia", descricao: "Advocacia moderna" },

  { id: 13, nome: "Festas Perfeitas", arquivo: "template-eventos1.html", json: "dados-eventos1.json", nicho: "eventos", descricao: "Festas e celebrações" },
  { id: 14, nome: "Prime Events", arquivo: "template-eventos2.html", json: "dados-eventos2.json", nicho: "eventos", descricao: "Eventos corporativos" },

  { id: 15, nome: "Clínica Vida & Saúde", arquivo: "template-medico1.html", json: "dados-medico1.json", nicho: "medico", descricao: "Clínica moderna" },
  { id: 16, nome: "Espaço Saúde Integral", arquivo: "template-medico2.html", json: "dados-medico2.json", nicho: "medico", descricao: "Centro médico" },

  { id: 17, nome: "Sabor & Arte Restaurante", arquivo: "template-restaurante1.html", json: "dados-restaurante1.json", nicho: "restaurante", descricao: "Restaurante elegante" },
  { id: 18, nome: "Bistrô Gourmet", arquivo: "template-restaurante2.html", json: "dados-restaurante2.json", nicho: "restaurante", descricao: "Bistrô moderno" },

  { id: 19, nome: "Silva & Advogados", arquivo: "template-advogados3.html", json: "dados-advogados3.json", nicho: "advocacia", descricao: "Escritório tradicional" },

  { id: 20, nome: "Institucional MultiPáginas", arquivo: "template-institucional/index.html", json: "template-institucional/config.json", nicho: "geral", descricao: "Multi páginas" }
];

const sourceDir = path.join(__dirname, "original_templates");
const destBase = path.join(__dirname, "resources", "templates");

if (!fs.existsSync(destBase)) fs.mkdirSync(destBase, { recursive: true });

for (const t of templates) {
  const slug = slugify(t.nome);
  const templateDir = path.join(destBase, slug);

  if (!fs.existsSync(templateDir)) fs.mkdirSync(templateDir);

  const htmlSrc = path.join(sourceDir, t.arquivo);
  const jsonSrc = path.join(sourceDir, t.json);

  const htmlDest = path.join(templateDir, "index.html");
  const jsonDest = path.join(templateDir, "config.json");

  if (fs.existsSync(htmlSrc) && !fs.existsSync(htmlDest)) {
    fs.copyFileSync(htmlSrc, htmlDest);
  }

  if (fs.existsSync(jsonSrc) && !fs.existsSync(jsonDest)) {
    fs.copyFileSync(jsonSrc, jsonDest);
  }

  console.log(`✅ ${slug} organizado`);
}

console.log("\n✨ Templates organizados com sucesso!\n");
