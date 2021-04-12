// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

using System;
using System.Collections.Generic;

namespace Genius.Code.Engine.Predictor
{
    /// <summary>
    /// Expert system engine looking for matching solutions.
    /// </summary>
    public sealed class Solver
    {
        private Engine.System _localExpertSystem;
        private Dictionary<int, Predictor.Response> _responses;
        private List<KeyValuePair<int, int>> _references;

        public Solver()
        {

        }

        /// <summary>
        /// Loads into the class memory an instance of the expert system on which operations will be performed.
        /// </summary>
        /// <param name="system">Expert system class instance containing <see cref="Engine.Condition"/>'s and <see cref="Engine.Product"/>'s.</param>
        public void LoadExpertSystem(Engine.System system)
        {
            this._localExpertSystem = system;
        }

        /// <summary>
        /// Collects identifiers of <see cref="Engine.Condition"/>'s and <see cref="Engine.Product"/>'s from which operations will be performed.
        /// </summary>
        public void BuildPredictionDictionary()
        {
            if (this._localExpertSystem == null)
                throw new InvalidOperationException("Expert System was not loaded, unable to create Prediction Dictionary. Check the Solver.LoadExpertSystem().");

            if(this._localExpertSystem.KnowledgeBase.References == null || this._localExpertSystem.KnowledgeBase.References.Count == 0)
                throw new InvalidOperationException("The Expert System has loaded, but the References list is empty. Check System.KnowledgeBase.AddReference().");

            this._references = new List<KeyValuePair<int, int>>();

            for (int i = 0; i < this._localExpertSystem.KnowledgeBase.References.Count; i++)
                this._references.Add(new KeyValuePair<int, int>(this._localExpertSystem.KnowledgeBase.References[i].ProductId, this._localExpertSystem.KnowledgeBase.References[i].ConditionId));
        }

        /// <summary>
        /// Adds a new <see cref="Predictor.Response"/> to the <see cref="Engine.Condition"/> or updates it.
        /// </summary>
        /// <param name="conditionId">Identifier of the <see cref="Engine.Condition"/> that was answered.</param>
        /// <param name="response">The <see cref="Predictor.Response"/> to the <see cref="Engine.Condition"/> that was given.</param>
        public void AddResponse(int conditionId, Predictor.Response response)
        {
            if (this._responses == null)
                this._responses = new Dictionary<int, Response> { };

            //Substitute the response if an answer has already been given.
            if (this._responses.ContainsKey(conditionId))
                this._responses[conditionId] = response;
            else
                this._responses.Add(conditionId, response);
        }
    }
}
