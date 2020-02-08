-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost
-- Üretim Zamanı: 08 Şub 2020, 19:06:05
-- Sunucu sürümü: 10.1.36-MariaDB
-- PHP Sürümü: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `current_project`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `38clinvar`
--

CREATE TABLE `38clinvar` (
  `clinvar_id` int(11) NOT NULL,
  `gene_id` int(11) NOT NULL,
  `allele_id` int(11) NOT NULL,
  `symbol` varchar(255) NOT NULL,
  `rs_number` varchar(255) NOT NULL,
  `rcv_accession` varchar(255) NOT NULL,
  `variation_id` int(11) NOT NULL,
  `variant_type` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` int(11) NOT NULL,
  `variation` varchar(255) NOT NULL,
  `clinical_significance` varchar(255) NOT NULL,
  `last_updated` varchar(255) NOT NULL,
  `phenotypes` varchar(255) NOT NULL,
  `cytogenetic` varchar(255) NOT NULL,
  `review_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `clinvar`
--

CREATE TABLE `clinvar` (
  `clinvar_id` bigint(20) DEFAULT NULL,
  `gene_id` bigint(20) DEFAULT NULL,
  `allele_id` bigint(20) DEFAULT NULL,
  `symbol` varchar(255) DEFAULT NULL,
  `rs_number` varchar(255) DEFAULT NULL,
  `rcv_accession` varchar(255) DEFAULT NULL,
  `variation_id` bigint(20) DEFAULT NULL,
  `variant_type` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `clinical_significance` varchar(255) DEFAULT NULL,
  `last_updated` varchar(255) DEFAULT NULL,
  `cytogenetic` varchar(255) DEFAULT NULL,
  `review_status` varchar(255) DEFAULT NULL,
  `phenotypes` varchar(255) DEFAULT NULL,
  `nm_id` text,
  `variation` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT '0',
  `np_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `conservation_scores`
--

CREATE TABLE `conservation_scores` (
  `cs_id` int(11) NOT NULL,
  `msa_id` int(11) NOT NULL,
  `aminoacid_number` int(11) NOT NULL,
  `specie` varchar(255) NOT NULL,
  `transcript_id_specie` varchar(255) NOT NULL,
  `score` int(11) NOT NULL,
  `score_type` varchar(255) NOT NULL,
  `transcript_id` varchar(255) NOT NULL,
  `gene_symbol` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `convart_gene`
--

CREATE TABLE `convart_gene` (
  `id` int(32) NOT NULL,
  `sequence` text NOT NULL,
  `species_id` varchar(55) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `convart_gene_to_db`
--

CREATE TABLE `convart_gene_to_db` (
  `convart_gene_id` int(11) NOT NULL,
  `db` varchar(25) NOT NULL,
  `db_id` varchar(50) NOT NULL,
  `db_id_version` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `CosmicMutantExport`
--

CREATE TABLE `CosmicMutantExport` (
  `cme_id` int(11) NOT NULL,
  `gene_name` varchar(255) DEFAULT NULL,
  `accession_number` varchar(255) DEFAULT NULL,
  `gene_cds_length` int(11) DEFAULT NULL,
  `hgnc_id` varchar(255) DEFAULT NULL,
  `sample_name` varchar(255) DEFAULT NULL,
  `id_sample` int(11) DEFAULT NULL,
  `id_tumour` int(11) DEFAULT NULL,
  `primary_site` varchar(255) DEFAULT NULL,
  `site_subtype_1` varchar(255) DEFAULT NULL,
  `site_subtype_2` varchar(255) DEFAULT NULL,
  `site_subtype_3` varchar(255) DEFAULT NULL,
  `primary_histology` varchar(255) DEFAULT NULL,
  `histology_subtype_1` varchar(255) DEFAULT NULL,
  `histology_subtype_2` varchar(255) DEFAULT NULL,
  `histology_subtype_3` varchar(255) DEFAULT NULL,
  `genome_wide_screen` varchar(255) DEFAULT NULL,
  `mutation_id` varchar(255) DEFAULT NULL,
  `mutation_cds` varchar(255) DEFAULT NULL,
  `mutation_aa` varchar(255) DEFAULT NULL,
  `mutation_description` varchar(255) DEFAULT NULL,
  `mutation_zygosity` varchar(255) DEFAULT NULL,
  `loh` varchar(255) DEFAULT NULL,
  `grch` varchar(255) DEFAULT NULL,
  `mutation_genome_position` varchar(255) DEFAULT NULL,
  `mutation_strand` varchar(255) DEFAULT NULL,
  `snp` varchar(255) DEFAULT NULL,
  `resistance_mutation` varchar(255) DEFAULT NULL,
  `fathmm_prediction` varchar(255) DEFAULT NULL,
  `fathmm_score` varchar(255) DEFAULT NULL,
  `mutation_somatic_status` varchar(255) DEFAULT NULL,
  `pubmed_pmid` varchar(255) DEFAULT NULL,
  `id_study` varchar(255) DEFAULT NULL,
  `sample_type` varchar(255) DEFAULT NULL,
  `tumour_origin` varchar(255) DEFAULT NULL,
  `age` varchar(255) DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `dbsnp`
--

CREATE TABLE `dbsnp` (
  `dbsnp_index` bigint(20) DEFAULT NULL,
  `Uploaded_variation` text,
  `Location` text,
  `Allele` text,
  `Gene` text,
  `Feature` varchar(255) DEFAULT NULL,
  `Feature_type` text,
  `Consequence` text,
  `cDNA_position` text,
  `CDS_position` text,
  `Protein_position` text,
  `HGVSp` text,
  `Impact` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `diseases_genes`
--

CREATE TABLE `diseases_genes` (
  `dg_id` int(11) NOT NULL,
  `gene_id` int(11) NOT NULL,
  `disease_id` varchar(255) NOT NULL,
  `dsi` float NOT NULL,
  `dpi` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `disease_info`
--

CREATE TABLE `disease_info` (
  `di_id` int(11) NOT NULL,
  `disease_id` varchar(255) NOT NULL,
  `disease_name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `domains`
--

CREATE TABLE `domains` (
  `domain_id` int(11) NOT NULL,
  `transcript_id` varchar(255) NOT NULL,
  `pfam_id` varchar(255) NOT NULL,
  `pfam_name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `start_point` int(11) NOT NULL,
  `end_point` int(11) NOT NULL,
  `bitscore` float NOT NULL,
  `evalue` varchar(10) NOT NULL,
  `clan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `domains_desc`
--

CREATE TABLE `domains_desc` (
  `domain_desc_id` int(11) NOT NULL,
  `pfam_id` varchar(11) NOT NULL,
  `pfam_name` varchar(255) NOT NULL,
  `domain_desc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `gnomad`
--

CREATE TABLE `gnomad` (
  `id` int(11) NOT NULL,
  `consequence` varchar(255) NOT NULL,
  `ensg_id` varchar(255) NOT NULL,
  `canonical_transcript` varchar(80) NOT NULL,
  `variation` varchar(50) NOT NULL,
  `filters` varchar(255) NOT NULL,
  `rs_number` varchar(50) NOT NULL,
  `variant_id` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `position` int(11) NOT NULL,
  `allele_count` int(11) NOT NULL,
  `allele_number` int(11) NOT NULL,
  `allelle_frequency` int(11) NOT NULL,
  `flags` varchar(255) NOT NULL,
  `datasets` varchar(512) NOT NULL,
  `is_canon` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `homology`
--

CREATE TABLE `homology` (
  `homology_id` int(11) NOT NULL,
  `human_gene_id` int(11) NOT NULL,
  `chimp_gene_id` varchar(255) NOT NULL,
  `macaque_gene_id` varchar(255) NOT NULL,
  `rat_gene_id` varchar(255) NOT NULL,
  `mouse_gene_id` varchar(255) NOT NULL,
  `zebrafish_gene_id` varchar(255) NOT NULL,
  `frog_gene_id` varchar(255) NOT NULL,
  `fruitfly_gene_id` varchar(255) NOT NULL,
  `worm_gene_id` varchar(255) NOT NULL,
  `yeast_gene_id` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `mapping_human`
--

CREATE TABLE `mapping_human` (
  `mh_id` int(11) NOT NULL,
  `gene_id` int(11) NOT NULL,
  `other_ids` varchar(255) NOT NULL,
  `gene_symbol` varchar(255) NOT NULL,
  `gene_synonyms` varchar(255) NOT NULL,
  `protein_numbers` varchar(255) NOT NULL,
  `gene_description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `missense_c_elegans`
--

CREATE TABLE `missense_c_elegans` (
  `row_names` text NOT NULL,
  `RefSeq.protein.ID` text,
  `WormBase.transcript.ID` text,
  `NCBI.Gene.ID` bigint(20) DEFAULT NULL,
  `WormBase.gene.ID` text,
  `Gene.name` text,
  `WormBase.Sequence.Name` text,
  `WormBase.var.ID` text,
  `Allele_name` text,
  `Mutation_type` text,
  `Variant_position` bigint(20) DEFAULT NULL,
  `Changes` text,
  `Aminoacid_length` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `mouse_variants`
--

CREATE TABLE `mouse_variants` (
  `id` int(11) NOT NULL,
  `pedigree` text,
  `chromosome` text,
  `coordinate` bigint(20) DEFAULT NULL,
  `ref` text,
  `gene_symbol` text,
  `ensembl_gene_id` text,
  `mutation_type` text,
  `pred_text` text,
  `pred_num` bigint(20) DEFAULT NULL,
  `ensembl_transcript_id` text,
  `aa_change` text,
  `Position` bigint(20) DEFAULT NULL,
  `pph_score` double DEFAULT NULL,
  `allele` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `msa`
--

CREATE TABLE `msa` (
  `id` int(11) NOT NULL,
  `fasta` mediumtext COLLATE latin1_bin NOT NULL,
  `alignment_method` varchar(255) CHARACTER SET utf8 NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `msa_best_combination`
--

CREATE TABLE `msa_best_combination` (
  `msa_id` int(11) NOT NULL,
  `convart_gene_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `msa_gene`
--

CREATE TABLE `msa_gene` (
  `msa_id` int(11) NOT NULL,
  `convart_gene_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ncbi_gene_meta`
--

CREATE TABLE `ncbi_gene_meta` (
  `ncbi_gene_id` int(11) NOT NULL,
  `meta_key` varchar(50) NOT NULL,
  `meta_value` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ptm`
--

CREATE TABLE `ptm` (
  `index_id` bigint(20) DEFAULT NULL,
  `gene` text,
  `protein` text,
  `acc_id` varchar(32) DEFAULT NULL,
  `hu_chr_loc` text,
  `mod_rsd` text,
  `site_grp_id` bigint(20) DEFAULT NULL,
  `organism` text,
  `mw_kd` double DEFAULT NULL,
  `domain` text,
  `site_+/-7_aa` text,
  `lt_lit` double DEFAULT NULL,
  `ms_lit` double DEFAULT NULL,
  `ms_cst` double DEFAULT NULL,
  `cst_cat#` text,
  `ptm_type` text,
  `enst_id` text,
  `position` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `species`
--

CREATE TABLE `species` (
  `id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tubulin_mutations`
--

CREATE TABLE `tubulin_mutations` (
  `id` int(11) NOT NULL,
  `organism` varchar(255) NOT NULL,
  `gene_tag` varchar(255) NOT NULL,
  `transcript` varchar(255) NOT NULL,
  `position` int(11) NOT NULL,
  `aa_change` varchar(255) NOT NULL,
  `phenotype` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `38clinvar`
--
ALTER TABLE `38clinvar`
  ADD PRIMARY KEY (`clinvar_id`),
  ADD KEY `gene_id` (`gene_id`),
  ADD KEY `gene_id_2` (`gene_id`),
  ADD KEY `gene_id_3` (`gene_id`);

--
-- Tablo için indeksler `clinvar`
--
ALTER TABLE `clinvar`
  ADD KEY `ix_clinvar_clinvar_id` (`clinvar_id`),
  ADD KEY `allele_id` (`allele_id`),
  ADD KEY `allele_id_2` (`allele_id`),
  ADD KEY `symbol` (`symbol`),
  ADD KEY `rs_number` (`rs_number`),
  ADD KEY `rcv_accession` (`rcv_accession`),
  ADD KEY `variation_id` (`variation_id`),
  ADD KEY `variant_type` (`variant_type`),
  ADD KEY `variant_type_2` (`variant_type`),
  ADD KEY `name` (`name`),
  ADD KEY `name_2` (`name`),
  ADD KEY `clinical_significance` (`clinical_significance`),
  ADD KEY `clinical_significance_2` (`clinical_significance`),
  ADD KEY `last_updated` (`last_updated`),
  ADD KEY `last_updated_2` (`last_updated`),
  ADD KEY `last_updated_3` (`last_updated`),
  ADD KEY `cytogenetic` (`cytogenetic`),
  ADD KEY `cytogenetic_2` (`cytogenetic`),
  ADD KEY `cytogenetic_3` (`cytogenetic`),
  ADD KEY `review_status` (`review_status`),
  ADD KEY `review_status_2` (`review_status`),
  ADD KEY `review_status_3` (`review_status`),
  ADD KEY `review_status_4` (`review_status`),
  ADD KEY `phenotypes` (`phenotypes`),
  ADD KEY `phenotypes_2` (`phenotypes`),
  ADD KEY `phenotypes_3` (`phenotypes`),
  ADD KEY `phenotypes_4` (`phenotypes`),
  ADD KEY `variation` (`variation`),
  ADD KEY `variation_2` (`variation`),
  ADD KEY `variation_3` (`variation`),
  ADD KEY `variation_4` (`variation`),
  ADD KEY `position` (`position`),
  ADD KEY `position_2` (`position`),
  ADD KEY `position_3` (`position`),
  ADD KEY `position_4` (`position`),
  ADD KEY `np_id` (`np_id`),
  ADD KEY `np_id_2` (`np_id`),
  ADD KEY `np_id_3` (`np_id`),
  ADD KEY `np_id_4` (`np_id`);

--
-- Tablo için indeksler `conservation_scores`
--
ALTER TABLE `conservation_scores`
  ADD PRIMARY KEY (`cs_id`),
  ADD KEY `transcript_id` (`transcript_id`),
  ADD KEY `msa_id` (`msa_id`);

--
-- Tablo için indeksler `convart_gene`
--
ALTER TABLE `convart_gene`
  ADD PRIMARY KEY (`id`),
  ADD KEY `species` (`species_id`);
ALTER TABLE `convart_gene` ADD FULLTEXT KEY `sequence` (`sequence`);

--
-- Tablo için indeksler `convart_gene_to_db`
--
ALTER TABLE `convart_gene_to_db`
  ADD PRIMARY KEY (`convart_gene_id`,`db`,`db_id`),
  ADD KEY `db` (`db`),
  ADD KEY `db_id` (`db_id`),
  ADD KEY `convart_gene_id` (`convart_gene_id`);

--
-- Tablo için indeksler `CosmicMutantExport`
--
ALTER TABLE `CosmicMutantExport`
  ADD PRIMARY KEY (`cme_id`),
  ADD KEY `accession_number` (`accession_number`),
  ADD KEY `gene_name` (`gene_name`),
  ADD KEY `sample_name` (`sample_name`),
  ADD KEY `primary_site` (`primary_site`),
  ADD KEY `primary_histology` (`primary_histology`),
  ADD KEY `mutation_id` (`mutation_id`),
  ADD KEY `mutation_cds` (`mutation_cds`),
  ADD KEY `mutation_aa` (`mutation_aa`),
  ADD KEY `mutation_description` (`mutation_description`),
  ADD KEY `fathmm_prediction` (`fathmm_prediction`),
  ADD KEY `fathmm_score` (`fathmm_score`),
  ADD KEY `mutation_somatic_status` (`mutation_somatic_status`),
  ADD KEY `pubmed_pmid` (`pubmed_pmid`),
  ADD KEY `tumour_origin` (`tumour_origin`),
  ADD KEY `position` (`position`);

--
-- Tablo için indeksler `dbsnp`
--
ALTER TABLE `dbsnp`
  ADD KEY `ix_dbsnp_index` (`dbsnp_index`),
  ADD KEY `Feature` (`Feature`);

--
-- Tablo için indeksler `diseases_genes`
--
ALTER TABLE `diseases_genes`
  ADD PRIMARY KEY (`dg_id`),
  ADD KEY `gene_id` (`gene_id`);

--
-- Tablo için indeksler `disease_info`
--
ALTER TABLE `disease_info`
  ADD PRIMARY KEY (`di_id`),
  ADD UNIQUE KEY `disease_id` (`disease_id`),
  ADD KEY `disease_name` (`disease_name`);

--
-- Tablo için indeksler `domains`
--
ALTER TABLE `domains`
  ADD PRIMARY KEY (`domain_id`),
  ADD KEY `transcript_id` (`transcript_id`);

--
-- Tablo için indeksler `domains_desc`
--
ALTER TABLE `domains_desc`
  ADD PRIMARY KEY (`domain_desc_id`),
  ADD UNIQUE KEY `pfam_id` (`pfam_id`);

--
-- Tablo için indeksler `gnomad`
--
ALTER TABLE `gnomad`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `canonical_transcript_2` (`canonical_transcript`,`variation`,`rs_number`),
  ADD KEY `canonical_transcript` (`canonical_transcript`),
  ADD KEY `ensg_id` (`ensg_id`),
  ADD KEY `rs_number` (`rs_number`),
  ADD KEY `consequence` (`consequence`),
  ADD KEY `is_canon` (`is_canon`);

--
-- Tablo için indeksler `homology`
--
ALTER TABLE `homology`
  ADD PRIMARY KEY (`homology_id`);

--
-- Tablo için indeksler `mapping_human`
--
ALTER TABLE `mapping_human`
  ADD PRIMARY KEY (`mh_id`),
  ADD UNIQUE KEY `gene_id` (`gene_id`);

--
-- Tablo için indeksler `missense_c_elegans`
--
ALTER TABLE `missense_c_elegans`
  ADD PRIMARY KEY (`row_names`(10));

--
-- Tablo için indeksler `mouse_variants`
--
ALTER TABLE `mouse_variants`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `msa`
--
ALTER TABLE `msa`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `msa_best_combination`
--
ALTER TABLE `msa_best_combination`
  ADD PRIMARY KEY (`msa_id`),
  ADD UNIQUE KEY `msa_id` (`msa_id`,`convart_gene_id`);

--
-- Tablo için indeksler `msa_gene`
--
ALTER TABLE `msa_gene`
  ADD PRIMARY KEY (`msa_id`,`convart_gene_id`),
  ADD KEY `convart_gene_id` (`convart_gene_id`),
  ADD KEY `msa_id` (`msa_id`);

--
-- Tablo için indeksler `ncbi_gene_meta`
--
ALTER TABLE `ncbi_gene_meta`
  ADD PRIMARY KEY (`ncbi_gene_id`,`meta_key`,`meta_value`),
  ADD KEY `meta_key` (`meta_key`),
  ADD KEY `meta_value` (`meta_value`) USING HASH,
  ADD KEY `ncbi_gene_id_2` (`ncbi_gene_id`) USING HASH;

--
-- Tablo için indeksler `ptm`
--
ALTER TABLE `ptm`
  ADD KEY `ix_ptm_index` (`index_id`),
  ADD KEY `acc_id` (`acc_id`);

--
-- Tablo için indeksler `species`
--
ALTER TABLE `species`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `tubulin_mutations`
--
ALTER TABLE `tubulin_mutations`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `38clinvar`
--
ALTER TABLE `38clinvar`
  MODIFY `clinvar_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `conservation_scores`
--
ALTER TABLE `conservation_scores`
  MODIFY `cs_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `convart_gene`
--
ALTER TABLE `convart_gene`
  MODIFY `id` int(32) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `CosmicMutantExport`
--
ALTER TABLE `CosmicMutantExport`
  MODIFY `cme_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `diseases_genes`
--
ALTER TABLE `diseases_genes`
  MODIFY `dg_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `disease_info`
--
ALTER TABLE `disease_info`
  MODIFY `di_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `domains`
--
ALTER TABLE `domains`
  MODIFY `domain_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `domains_desc`
--
ALTER TABLE `domains_desc`
  MODIFY `domain_desc_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `gnomad`
--
ALTER TABLE `gnomad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `homology`
--
ALTER TABLE `homology`
  MODIFY `homology_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `mapping_human`
--
ALTER TABLE `mapping_human`
  MODIFY `mh_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `mouse_variants`
--
ALTER TABLE `mouse_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `msa`
--
ALTER TABLE `msa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `tubulin_mutations`
--
ALTER TABLE `tubulin_mutations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
