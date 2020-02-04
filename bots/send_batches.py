import os
folder = '/opt/lampp/htdocs/current_project/bots/'
with open(os.path.join(folder,'ensembl_gene_ids.csv'), 'r') as f:
	n_elements = len(f.readlines())

per_thread = 32000
for i in range(0,int(n_elements/per_thread)):
	os.system("nohup php -d memory_limit=4096M "+folder+"gnomad.php "+str(i*per_thread)+" " + str((i+1)*per_thread) +" > nohup_"+str(i)+".out 2>&1 &")

