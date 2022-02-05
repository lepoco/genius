using Genius.Expert.Interfaces;
using System.Collections.Generic;

namespace Genius.Expert
{
    public class SolverQuestion : ISolverQuestion
    {
        public int SystemId { get; set; } = 0;

        public bool IsMultiple { get; set; } = true;

        public IEnumerable<int> Confirming { get; set; } = new List<int>();

        public IEnumerable<int> Negating { get; set; } = new List<int>();

        public IEnumerable<int> Indifferent { get; set; } = new List<int>();
    }
}