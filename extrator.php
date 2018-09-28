<?php
/**
* Classe para extrair dados do arquivo de log do Quake.
*
* @author Fernando Gonçalves Rodrigues
* @date 27/09/2018
*/


//Constante que define a string padrão que identifica mortes no jogo.
const DEF_MORTES = 'killed';
//Constante que define a string padrão que identifica mortes que não foram causadas por players.
const WORLD = 'world';
//Constante que define a string que identifica a causa das mortes.
const CAUSA_MORTE = 'by';
//Constante com uma lista de strings de palavra indesejáveis.
const RETIRAR = ['Kill','score','ping','client'];
//Constante a strings que define os players.
const DEF_PLAYERS = 'client:';

class QuakeLog
{
	/**
	* Função que realiza a leitura do arquivo do relatorio de log.
	*
	* @param  string $arquivo
	* @return string 
	*/
	public function lerArquivo($arquivo)
	{		
		$arquivo = fopen ($arquivo, "r");

		$extrato = $this->extrairDados($arquivo);
		
		fclose ($arquivo);

		return $extrato;
	}

	/**
	* Função que extrai os dados do relatorio de log.
	*
	* @param  string $arquivo
	* @return string 
	*/
	private function extrairDados($log)
	{
		$palyers = [];
		$kills = [];
		$tiposMortes = [];
		$killers = [];
		$tipoMortePlayer = [];

		while (! feof ($log))
		{
			$linhas = fgets ($log, 4096);
			//extrai somente as linhas que possuem a string definida em DEF_MORTES.
			if(preg_match ('/' . DEF_MORTES . '/', $linhas)){
				
				$dadosMatou = $this->extrair ($linhas, 'qtd_matou');
				array_push ($killers, $dadosMatou);

				$dadosTipoMortePlayer = $this->extrair ($linhas, 'tipo_morte_player');
				array_push ($tipoMortePlayer , $dadosTipoMortePlayer);

				$dadosMortes = $this->extrair ($linhas , 'mortes');
				array_push ($kills,$dadosMortes);

				$dadosTiposMortes = $this->extrair ($linhas, 'tipo_morte');	
					
				//verifica se o tipo de morte ja foi adicionado.
				if (! in_array (key ($dadosTiposMortes), $tiposMortes)) { 
					array_push ($tiposMortes, key ($dadosTiposMortes));
				}
			}
			//extrai somente as linhas que possuem a string definida em DEF_PLAYERS.
			if(preg_match('/' . DEF_PLAYERS . '/', $linhas)){
				//verifica se o player ja foi adicionado.
				$dados_players = $this->extrair ($linhas , 'player');
				if (! in_array ($dados_players, $palyers)) { 
					array_push ($palyers, $dados_players);
				}
			}
		}

		foreach ($palyers as $value) {

			$killPlayer[$value] = count (array_column ($kills, $value));

			$killersPlayers[$value] = count (array_column ($killers, $value));
		}

		foreach ($tiposMortes as $key => $value) {

			$killTipos[$value] = count (array_column ($tipoMortePlayer, $value));
		}

		$relatorio['game-1']['total_kills'] = count ($kills);
		$relatorio['game-1']['players'] = $palyers;
		$relatorio['game-1']['kills'] = $killPlayer;
		$relatorio['game-1']['killers'] = $killersPlayers;
		$relatorio['game-1']['kills_by_means'] = $killTipos;

		return $relatorio;
	}

 	/**
	* Função que extrai dados relacionados a mortes no jogo.
	*
	* @param  string $linhas
	* @param  string $tipo
	* @return mixed array|string 
	*/
	private function extrair($linhas,$tipo)
	{
		//Trata a stirng para que tenhamos somente dados relevantes.	 
		$dados_morte = $this->tratarString($linhas);
		//Separa o player que matou de quem morreu
		$dados_morte = explode( DEF_MORTES , $dados_morte);
		//Separa o player que morreu e a causa da morte
		$causa_morte = explode( CAUSA_MORTE , $dados_morte[1]);

		if($tipo == 'mortes'){
		   $lista[trim($causa_morte[0])] = trim($causa_morte[1]);
		}

		if($tipo == 'qtd_matou'){
			$lista[trim($dados_morte[0])] = trim($dados_morte[0]);
		}

		if($tipo == 'tipo_morte_player'){
		   $lista[trim($causa_morte[1])] = trim($causa_morte[0]);
		}

		if($tipo == 'tipo_morte'){
			$lista[trim($causa_morte[1])][] = trim($causa_morte[1]);
		}	

		if($tipo == 'player'){
			$lista = $dados_morte[0];	
		}

		return   $lista;
	}

	/**
	* Função que trata a string removendo informações não relevantes.
	*
	* @param  string $linhas
	* @return string 
	*/
	private function tratarString($linhas)
	{
		$string_tratada = str_replace (RETIRAR,'', $linhas);
		$string_tratada = preg_replace ('/[^a-z_ ]/i', ' ', $string_tratada);
		$string_tratada = trim ($string_tratada);
		 
		return $string_tratada;
	}

}

$nome_aquivo = 'games.log';
$quakeLog = new QuakeLog;

echo json_encode ($quakeLog->lerArquivo($nome_aquivo));
?>
